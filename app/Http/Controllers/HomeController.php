<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function recognize(Request $request)
    {
        $request->validate(['audio' => 'required|file|max:10240']);

        // --- CONFIG ---
        $host = "identify-ap-southeast-1.acrcloud.com";
        $accessKey = env('ACR_ACCESS_KEY');
        $accessSecret = env('ACR_ACCESS_SECRET');
        $httpMethod = "POST";
        $httpUri = "/v1/identify";
        $dataType = "audio";
        $signatureVersion = "1";
        $timestamp = time();

        $stringToSign = $httpMethod . "\n" . $httpUri . "\n" . $accessKey . "\n" . $dataType . "\n" . $signatureVersion . "\n" . $timestamp;
        $signature = base64_encode(hash_hmac("sha1", $stringToSign, $accessSecret, true));
        $file = $request->file('audio');
        $fileContent = file_get_contents($file->getRealPath());

        try {
            $response = Http::withoutVerifying()->asMultipart()->post("https://" . $host . $httpUri, [
                'access_key' => $accessKey,
                'data_type' => $dataType,
                'signature_version' => $signatureVersion,
                'signature' => $signature,
                'timestamp' => $timestamp,
                'sample' => $fileContent,
            ]);

            $json = $response->json();

            // 👇 DEBUG: This logs exactly what ACRCloud found (or didn't find)
            // Check storage/logs/laravel.log to see the raw scores!
            Log::info('ACRCloud Raw Response:', $json);

            // Check for Success (Code 0) OR "Partial Match" (sometimes Code 1001 acts weird)
            if (isset($json['status']['code']) && $json['status']['code'] == 0) {

                $matches = $json['metadata']['music'];
                $bestMatch = null;
                $highestWeightedScore = -1;

                // --- 1. NUCLEAR SELECTION LOGIC ---
                foreach ($matches as $match) {
                    $rawScore = $match['score'];
                    $boost = 0;

                    // Boost Official Tracks slightly (just to break ties)
                    if (isset($match['external_metadata']['spotify']) || isset($match['external_metadata']['youtube'])) {
                        $boost += 10;
                    }

                    $finalScore = $rawScore + $boost;

                    // 🛑 NO THRESHOLD: We accept EVERYTHING.
                    // Even if score is 10, we compare it.
                    if ($finalScore > $highestWeightedScore) {
                        $highestWeightedScore = $finalScore;
                        $bestMatch = $match;
                    }
                }

                // Fallback: If the logic above somehow failed, force the first result.
                if (!$bestMatch && isset($matches[0])) {
                    $bestMatch = $matches[0];
                }

                if (!$bestMatch) {
                    return response()->json(['status' => 'error', 'message' => 'No music detected.']);
                }

                // --- 2. DATA EXTRACTION ---
                $title = $bestMatch['title'];
                $artist = $bestMatch['artists'][0]['name'] ?? "Unknown Artist";

                // --- 3. LINKS (Standardized) ---
                $spotifyLink = null;
                if (!empty($bestMatch['external_metadata']['spotify']['track']['id'])) {
                    // Use standard Spotify Link format
                    $spotifyLink = "https://open.spotify.com/track/" . $bestMatch['external_metadata']['spotify']['track']['id'];
                }

                $youtubeVid = null;
                if (!empty($bestMatch['external_metadata']['youtube']['vid'])) {
                    $youtubeVid = $bestMatch['external_metadata']['youtube']['vid'];
                    $youtubeLink = "https://www.youtube.com/watch?v=" . $youtubeVid;
                } else {
                    $searchQuery = urlencode($title . " " . $artist);
                    $youtubeLink = "https://www.youtube.com/results?search_query={$searchQuery}";
                }

                // --- 4. IMAGE WATERFALL ---
                $albumArt = null;

                // Priority A: Spotify Metadata
                if (!empty($bestMatch['external_metadata']['spotify']['album']['images'][0]['url'])) {
                    $albumArt = $bestMatch['external_metadata']['spotify']['album']['images'][0]['url'];
                }
                // Priority B: YouTube Thumbnail
                elseif ($youtubeVid) {
                    $albumArt = "https://i.ytimg.com/vi/$youtubeVid/hqdefault.jpg";
                }
                // Priority C: Deezer / Generic
                elseif (!empty($bestMatch['external_metadata']['deezer']['album']['cover_xl'])) {
                    $albumArt = $bestMatch['external_metadata']['deezer']['album']['cover_xl'];
                }
                elseif (!empty($bestMatch['album']['cover'])) {
                    $albumArt = $bestMatch['album']['cover'];
                }

                // --- 5. IMAGE FALLBACKS ---

                // Fallback A: Spotify oEmbed
                if (!$albumArt && $spotifyLink) {
                    try {
                        // Use the link we just created
                        $oembedUrl = "https://open.spotify.com/oembed?url=" . $spotifyLink;
                        $oembed = Http::get($oembedUrl)->json();
                        $albumArt = $oembed['thumbnail_url'] ?? null;
                    } catch (\Exception $e) {}
                }

                // Fallback B: Ultimate iTunes Search
                if (!$albumArt) {
                    $albumArt = $this->getItunesCover($title, $artist);
                }

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title' => $title,
                        'artist' => $artist,
                        'album_art' => $albumArt,
                        'spotify_link' => $spotifyLink,
                        'youtube_link' => $youtubeLink,
                        'debug_score' => $bestMatch['score'] // Check console to see how low the score was!
                    ]
                ]);
            }

            // If we are here, ACRCloud sent Status 1001 (Empty List)
            // This means even THEY couldn't find it.
            return response()->json(['status' => 'error', 'message' => 'No match found in database.']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function cleanTitle($title)
    {
        $title = preg_replace('/\s*[\(\[].*?[\)\]]/', '', $title);
        $title = preg_replace('/\s(feat|ft)\..*/i', '', $title);
        return trim($title);
    }

    private function getItunesCover($title, $artist)
    {
        $cleanTitle = $this->cleanTitle($title);
        $markets = ['US', 'GB', 'IN', 'PK', 'SA', 'EG', 'MX', 'BR', 'JP', 'DE', 'FR']; // Added DE/FR for Europe

        foreach ($markets as $country) {
            try {
                $term = urlencode($cleanTitle . ' ' . $artist);
                $response = Http::timeout(1.5)->get("https://itunes.apple.com/search?term={$term}&media=music&entity=song&limit=1&country={$country}");
                $data = $response->json();

                if (!empty($data['results'][0]['artworkUrl100'])) {
                    return str_replace('100x100bb', '600x600bb', $data['results'][0]['artworkUrl100']);
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return null;
    }
}
