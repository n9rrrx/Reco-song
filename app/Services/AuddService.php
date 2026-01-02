<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuddService
{
    /**
     * Recognize a song using Audd.io API
     * Free tier: 300 requests/day
     * 
     * @param string $audioPath Path to audio file
     * @return array|null
     */
    public function recognize(string $audioPath): ?array
    {
        $apiToken = env('AUDD_API_TOKEN');
        
        // If no API token configured, skip this service
        if (empty($apiToken)) {
            Log::info('Audd.io: No API token configured, skipping');
            return null;
        }

        try {
            // withoutVerifying() needed for Windows local dev SSL issues
            $response = Http::withoutVerifying()
                ->timeout(10)
                ->attach('file', file_get_contents($audioPath), 'audio.wav')
                ->post('https://api.audd.io/', [
                    'api_token' => $apiToken,
                    'return' => 'spotify,apple_music,deezer',
                ]);

            $data = $response->json();
            Log::info('Audd.io Response:', $data);

            if (isset($data['status']) && $data['status'] === 'success' && !empty($data['result'])) {
                $result = $data['result'];
                
                return [
                    'title' => $result['title'] ?? 'Unknown Title',
                    'artist' => $result['artist'] ?? 'Unknown Artist',
                    'album' => $result['album'] ?? null,
                    'release_date' => $result['release_date'] ?? null,
                    'spotify_id' => $result['spotify']['id'] ?? null,
                    'apple_music_url' => $result['apple_music']['url'] ?? null,
                    'album_art' => $this->extractAlbumArt($result),
                    'source' => 'audd',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Audd.io Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract album art from various sources in the response
     */
    private function extractAlbumArt(array $result): ?string
    {
        // Try Spotify first
        if (!empty($result['spotify']['album']['images'][0]['url'])) {
            return $result['spotify']['album']['images'][0]['url'];
        }
        
        // Try Apple Music
        if (!empty($result['apple_music']['artwork']['url'])) {
            $url = $result['apple_music']['artwork']['url'];
            // Apple Music uses {w}x{h} placeholders
            return str_replace(['{w}', '{h}'], ['600', '600'], $url);
        }
        
        // Try Deezer
        if (!empty($result['deezer']['album']['cover_xl'])) {
            return $result['deezer']['album']['cover_xl'];
        }

        return null;
    }
}
