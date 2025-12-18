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

        // 1. THE 5-HOUR BRIDGE: This MUST stay as is to match your server's clock lag
        $timestamp = time() + 18000;

        // 2. SIGNATURE LOGIC: Pulling secrets securely from .env
        // Note: The order here (POST, URI, Key, Type, Version, Time) is what fixes Error 3014
        $stringToSign = "POST\n/v1/identify\n" . env('ACR_ACCESS_KEY') . "\naudio\n1\n" . $timestamp;
        $signature = base64_encode(hash_hmac("sha1", $stringToSign, env('ACR_ACCESS_SECRET'), true));

        $file = $request->file('audio');

        try {
            // 3. THE MULTIPART REQUEST
            // Pulls the host dynamically from your .env
            $response = Http::withoutVerifying()
                ->attach('sample', file_get_contents($file->getRealPath()), 'recording.wav')
                ->post("https://" . env('ACR_HOST') . "/v1/identify", [
                    'access_key' => env('ACR_ACCESS_KEY'),
                    'data_type' => 'audio',
                    'signature_version' => '1',
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                    'sample_bytes' => $file->getSize(),
                ]);

            $json = $response->json();
            Log::info('ACRCloud Response:', $json);

            if (isset($json['status']['code']) && $json['status']['code'] == 0) {
                $bestMatch = $json['metadata']['music'][0];
                $title = $bestMatch['title'];
                $artist = $bestMatch['artists'][0]['name'] ?? "Unknown Artist";

                // --- INTEGRATIONS ---
                $spotifyId = $bestMatch['external_metadata']['spotify']['track']['id'] ?? null;
                $youtubeVid = $bestMatch['external_metadata']['youtube']['vid'] ?? null;

                $albumArt = $bestMatch['external_metadata']['spotify']['album']['images'][0]['url']
                    ?? ($youtubeVid ? "https://i.ytimg.com/vi/{$youtubeVid}/hqdefault.jpg" : $this->getItunesCover($title, $artist));

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title' => $title,
                        'artist' => $artist,
                        'album_art' => $albumArt,
                        'spotify_id' => $spotifyId, // For the interactive player
                        'youtube_link' => $youtubeVid ? "https://www.youtube.com/watch?v={$youtubeVid}" : "https://www.youtube.com/results?search_query=" . urlencode("$title $artist"),
                    ]
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Song not found. 💀']);

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
        $markets = ['US', 'GB', 'IN', 'PK', 'SA', 'EG', 'MX', 'BR', 'JP', 'DE', 'FR'];

        foreach ($markets as $country) {
            try {
                $term = urlencode($cleanTitle . ' ' . $artist);
                $response = Http::timeout(1.0)->get("https://itunes.apple.com/search?term={$term}&media=music&entity=song&limit=1&country={$country}");
                $data = $response->json();

                if (!empty($data['results'][0]['artworkUrl100'])) {
                    return str_replace('100x100bb', '600x600bb', $data['results'][0]['artworkUrl100']);
                }
            } catch (\Exception $e) { continue; }
        }
        return null;
    }
}
