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

        // ... (Keep your Config lines 20-40 exactly the same) ...
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
            $response = Http::withoutVerifying()
                ->asMultipart()
                ->post("https://" . $host . $httpUri, [
                    'access_key' => $accessKey,
                    'data_type' => $dataType,
                    'signature_version' => $signatureVersion,
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                    'sample' => $fileContent,
                ]);

            $json = $response->json();

            // 👇 DEBUG: This saves the raw data to storage/logs/laravel.log
            Log::info('ACRCloud Response:', $json);

            if (isset($json['status']['code']) && $json['status']['code'] == 0) {

                $music = $json['metadata']['music'][0];
                $title = $music['title'];
                $artist = $music['artists'][0]['name'] ?? "Unknown Artist";

                // 👇 ROBUST IMAGE FINDER
                $albumArt = null;

                // 1. Try Spotify (Best Quality)
                if (!empty($music['external_metadata']['spotify']['album']['images'][0]['url'])) {
                    $albumArt = $music['external_metadata']['spotify']['album']['images'][0]['url'];
                }
                // 2. Try Deezer (High Quality)
                elseif (!empty($music['external_metadata']['deezer']['album']['cover_xl'])) {
                    $albumArt = $music['external_metadata']['deezer']['album']['cover_xl'];
                }
                // 3. Try YouTube (Reliable Fallback)
                elseif (!empty($music['external_metadata']['youtube']['vid'])) {
                    $vid = $music['external_metadata']['youtube']['vid'];
                    $albumArt = "https://i.ytimg.com/vi/$vid/hqdefault.jpg";
                }
                // 4. Try Standard ACRCloud Cover
                elseif (!empty($music['album']['cover'])) {
                    $albumArt = $music['album']['cover'];
                }

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title' => $title,
                        'artist' => $artist,
                        'album_art' => $albumArt,
                    ]
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Song not found']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
