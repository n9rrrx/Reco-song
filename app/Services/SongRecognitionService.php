<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SongRecognitionService
{
    private ACRCloudService $acrCloud;
    private AuddService $audd;
    private AcoustIDService $acoustId;

    public function __construct()
    {
        $this->acrCloud = new ACRCloudService();
        $this->audd = new AuddService();
        $this->acoustId = new AcoustIDService();
    }

    /**
     * Recognize a song using multiple services in fallback chain
     * Priority: ACRCloud → Audd.io → AcoustID
     * 
     * @param string $audioPath Path to the audio file
     * @param int $fileSize Size of the audio file
     * @return array
     */
    public function recognize(string $audioPath, int $fileSize): array
    {
        Log::info('Starting multi-service recognition chain');
        $startTime = microtime(true);

        // 1. Try ACRCloud first (fastest, best for mainstream)
        Log::info('Trying ACRCloud...');
        $result = $this->acrCloud->recognize($audioPath, $fileSize);
        
        if ($result) {
            $result['recognition_time'] = round((microtime(true) - $startTime) * 1000);
            Log::info("ACRCloud matched in {$result['recognition_time']}ms");
            return $this->formatSuccessResponse($result);
        }

        // 2. Fallback to Audd.io (great for international music)
        Log::info('ACRCloud failed, trying Audd.io...');
        $result = $this->audd->recognize($audioPath);
        
        if ($result) {
            $result['recognition_time'] = round((microtime(true) - $startTime) * 1000);
            Log::info("Audd.io matched in {$result['recognition_time']}ms");
            return $this->formatSuccessResponse($result);
        }

        // 3. Final fallback to AcoustID (best for instrumentals)
        Log::info('Audd.io failed, trying AcoustID...');
        $result = $this->acoustId->recognize($audioPath);
        
        if ($result) {
            $result['recognition_time'] = round((microtime(true) - $startTime) * 1000);
            Log::info("AcoustID matched in {$result['recognition_time']}ms");
            return $this->formatSuccessResponse($result);
        }

        // No matches found in any service
        $totalTime = round((microtime(true) - $startTime) * 1000);
        Log::info("No matches found in any service after {$totalTime}ms");
        
        return [
            'status' => 'error',
            'message' => 'No match found. Try recording clearer audio! 🎵',
            'recognition_time' => $totalTime,
        ];
    }

    /**
     * Format successful recognition result
     */
    private function formatSuccessResponse(array $result): array
    {
        // Generate YouTube link
        $youtubeLink = null;
        if (!empty($result['youtube_id'])) {
            $youtubeLink = "https://www.youtube.com/watch?v={$result['youtube_id']}";
        } else {
            // Search YouTube as fallback
            $searchQuery = urlencode("{$result['title']} {$result['artist']}");
            $youtubeLink = "https://www.youtube.com/results?search_query={$searchQuery}";
        }

        // Try to get album art from multiple sources if not present
        if (empty($result['album_art'])) {
            $youtubeId = $result['youtube_id'] ?? null;
            $result['album_art'] = $this->getAlbumArt($result['title'], $result['artist'], $youtubeId);
        }

        return [
            'status' => 'success',
            'data' => [
                'title' => $result['title'],
                'artist' => $result['artist'],
                'album' => $result['album'] ?? null,
                'album_art' => $result['album_art'],
                'spotify_id' => $result['spotify_id'] ?? null,
                'youtube_link' => $youtubeLink,
                'source' => $result['source'],
                'recognition_time' => $result['recognition_time'] ?? null,
            ],
        ];
    }

    /**
     * Get album cover from multiple sources (iTunes, Deezer, YouTube)
     */
    private function getAlbumArt(string $title, string $artist, ?string $youtubeId = null): ?string
    {
        $cleanTitle = preg_replace('/\s*[\(\[].*?[\)\]]/', '', $title);
        $cleanTitle = preg_replace('/\s(feat|ft)\..*/i', '', $cleanTitle);
        $searchTerm = trim($cleanTitle) . ' ' . $artist;
        
        // 1. Try iTunes (best quality covers)
        $cover = $this->tryItunes($searchTerm);
        if ($cover) return $cover;
        
        // 2. Try Deezer
        $cover = $this->tryDeezer($searchTerm);
        if ($cover) return $cover;
        
        // 3. Try Last.fm
        $cover = $this->tryLastFm($artist, $cleanTitle);
        if ($cover) return $cover;
        
        // 4. Use YouTube thumbnail as last resort
        if ($youtubeId) {
            return "https://i.ytimg.com/vi/{$youtubeId}/maxresdefault.jpg";
        }
        
        return null;
    }
    
    private function tryItunes(string $term): ?string
    {
        $markets = ['US', 'GB', 'IN', 'PK', 'JP', 'KR', 'DE', 'FR', 'BR', 'MX'];
        
        foreach ($markets as $country) {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(2)
                    ->get("https://itunes.apple.com/search", [
                        'term' => $term,
                        'media' => 'music',
                        'entity' => 'song',
                        'limit' => 1,
                        'country' => $country
                    ]);
                
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
    
    private function tryDeezer(string $term): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(2)
                ->get("https://api.deezer.com/search", [
                    'q' => $term,
                    'limit' => 1
                ]);
            
            $data = $response->json();
            if (!empty($data['data'][0]['album']['cover_xl'])) {
                return $data['data'][0]['album']['cover_xl'];
            }
        } catch (\Exception $e) {
            // Deezer failed, continue
        }
        return null;
    }
    
    private function tryLastFm(string $artist, string $track): ?string
    {
        try {
            // Last.fm API (no key needed for basic search)
            $response = Http::withoutVerifying()
                ->timeout(2)
                ->get("https://ws.audioscrobbler.com/2.0/", [
                    'method' => 'track.search',
                    'track' => $track,
                    'artist' => $artist,
                    'api_key' => 'b25b959554ed76058ac220b7b2e0a026', // Public demo key
                    'format' => 'json',
                    'limit' => 1
                ]);
            
            $data = $response->json();
            $results = $data['results']['trackmatches']['track'] ?? [];
            if (!empty($results[0]['image'])) {
                // Get the largest image
                $images = $results[0]['image'];
                foreach (array_reverse($images) as $img) {
                    if (!empty($img['#text']) && !str_contains($img['#text'], 'lastfm')) {
                        return $img['#text'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Last.fm failed
        }
        return null;
    }
}
