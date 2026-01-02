<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ACRCloudService
{
    /**
     * Recognize a song using ACRCloud API
     * 
     * @param string $audioPath Path to audio file
     * @param int $fileSize Size of the audio file
     * @return array|null
     */
    public function recognize(string $audioPath, int $fileSize): ?array
    {
        $host = env('ACR_HOST');
        $accessKey = env('ACR_ACCESS_KEY');
        $accessSecret = env('ACR_ACCESS_SECRET');

        if (empty($host) || empty($accessKey) || empty($accessSecret)) {
            Log::info('ACRCloud: Missing configuration, skipping');
            return null;
        }

        // Time offset for server clock sync
        $timestamp = time() + 18000;

        // Generate signature
        $stringToSign = "POST\n/v1/identify\n{$accessKey}\naudio\n1\n{$timestamp}";
        $signature = base64_encode(hash_hmac("sha1", $stringToSign, $accessSecret, true));

        try {
            $response = Http::withoutVerifying()
                ->attach('sample', file_get_contents($audioPath), 'recording.wav')
                ->post("https://{$host}/v1/identify", [
                    'access_key' => $accessKey,
                    'data_type' => 'audio',
                    'signature_version' => '1',
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                    'sample_bytes' => $fileSize,
                ]);

            $json = $response->json();
            Log::info('ACRCloud Response:', $json);

            if (isset($json['status']['code']) && $json['status']['code'] == 0) {
                $bestMatch = $json['metadata']['music'][0];
                
                return [
                    'title' => $bestMatch['title'],
                    'artist' => $bestMatch['artists'][0]['name'] ?? 'Unknown Artist',
                    'album' => $bestMatch['album']['name'] ?? null,
                    'spotify_id' => $bestMatch['external_metadata']['spotify']['track']['id'] ?? null,
                    'youtube_id' => $bestMatch['external_metadata']['youtube']['vid'] ?? null,
                    'album_art' => $this->extractAlbumArt($bestMatch),
                    'source' => 'acrcloud',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::error('ACRCloud Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract album art from ACRCloud response
     */
    private function extractAlbumArt(array $match): ?string
    {
        // Try Spotify album art first
        if (!empty($match['external_metadata']['spotify']['album']['images'][0]['url'])) {
            return $match['external_metadata']['spotify']['album']['images'][0]['url'];
        }

        // Try YouTube thumbnail
        if (!empty($match['external_metadata']['youtube']['vid'])) {
            return "https://i.ytimg.com/vi/{$match['external_metadata']['youtube']['vid']}/hqdefault.jpg";
        }

        return null;
    }
}
