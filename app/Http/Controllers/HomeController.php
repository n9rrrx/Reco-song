<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function recognize(Request $request)
    {
        // 1. Validate that an audio file was actually sent
        $request->validate([
            'audio' => 'required|file|mimes:webm,wav,mp4,mp3|max:10240', // Max 10MB
        ]);

        try {
            // 2. Store the file temporarily to check if upload works
            if ($request->hasFile('audio')) {
                $file = $request->file('audio');
                $path = $file->store('recordings', 'public');

                Log::info("Audio uploaded successfully: " . $path);

                // 3. MOCK RESPONSE (Simulating a successful API hit)
                // We will replace this with the real AudD/ACRCloud API call next.
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'title'  => 'Adventure of a Lifetime (TEST)',
                        'artist' => 'Coldplay',
                        'album'  => 'A Head Full of Dreams',
                        'album_art' => '/assets/images/misc/plan.png',
                        'link_spotify' => 'https://open.spotify.com/track/69uxyAqqPIsUyTO8txoP2M'
                    ]
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);

        } catch (\Exception $e) {
            Log::error("Recognition Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Server Error'], 500);
        }
    }
}
