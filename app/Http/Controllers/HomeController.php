<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application's home/recognizer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // This method simply loads your main view (resources/views/home.blade.php)
        return view('home');
    }

    /**
     * Handle the recognition request (Live Listener or Link Drop).
     * We will build this logic later.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recognize(Request $request)
    {
        // Placeholder for the recognition logic (AudD/ACRCloud API calls)
        return response()->json(['message' => 'Recognition endpoint reached successfully.']);
    }
}
