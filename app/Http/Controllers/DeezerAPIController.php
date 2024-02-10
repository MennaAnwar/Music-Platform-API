<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class DeezerAPIController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');
        $response = Http::get("https://api.deezer.com/search", ['q' => $query]);
        return response()->json($response->json());
    }

    public function ArtistDetails(Request $request)
    {
        $artist = $request->query('artist');
        $url = "https://api.deezer.com/artist/" . $artist;
        $response = Http::get($url);
        return response()->json($response->json());
    }

    public function Genres(){
        $url = "https://api.deezer.com/genre";
        $response =  Http::get($url);
        return response()->json($response->json());
    }
}
