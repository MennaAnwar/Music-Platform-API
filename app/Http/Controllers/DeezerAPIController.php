<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class DeezerAPIController extends Controller
{
    public function search(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }
        $query = $request->query('q');
        $response = Http::get("https://api.deezer.com/search", ['q' => $query]);
        return response()->json($response->json());
    }

    public function ArtistDetails(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $artist = $request->query('artist');
        $artistUrl = "https://api.deezer.com/artist/" . $artist;

        $artistResponse = Http::get($artistUrl);

        if ($artistResponse->successful()) {
            $artistId =  $artistResponse->json()['id'];
            $tracklistUrl = $artistResponse->json()['tracklist'] ?? null;
            $albumsUrl = "https://api.deezer.com/artist/" . $artistId . "/albums";

            $responses = [
                'artist' => $artistResponse->json()
            ];

            if ($tracklistUrl) {
                $tracksResponse = Http::get($tracklistUrl);
                if ($tracksResponse->successful()) {
                    $responses['tracks'] = $tracksResponse->json();
                } else {
                    $responses['tracksError'] = 'Failed to fetch artist tracks.';
                }
            }

            $albumsResponse = Http::get($albumsUrl);
            if ($albumsResponse->successful()) {
                $responses['albums'] = $albumsResponse->json();
            } else {
                $responses['albumsError'] = 'Failed to fetch artist albums.';
            }

            return response()->json($responses);
        } else {
            return response()->json([
                'error' => 'Failed to fetch artist details.'
            ], $artistResponse->status());
        }
    }

    public function Genres(){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $url = "https://api.deezer.com/genre";
        $response =  Http::get($url);
        return response()->json($response->json());
    }

    public function Charts(){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $url = "https://api.deezer.com/chart";
        $response =  Http::get($url);
        return response()->json($response->json());
    }

    public function Track(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }
        
        $songId = $request->query('songId');
        $url = "https://api.deezer.com/track/". $songId;
        $response =  Http::get($url);
        return response()->json($response->json());
    }
}
