<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SongController extends Controller
{
    public function getSongsForPlaylist(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlist = Playlist::with('songs')->find($request->playlistId);

        if (!$playlist) {
            return response()->json(['message' => 'Playlist not found'], 404);
        }

        $songDetailsArray = $playlist->songs->map(function ($song) {
            $response = Http::get("https://api.deezer.com/track/{$song->song_Id}");
            if ($response->successful()) {
                return $response->json(); 
            }
            return null; 
        })->filter()->values(); 

        return response()->json($songDetailsArray);
    }

    public function deleteSongFromPlaylist(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlistId = $request->playlistId;
        $songId = $request->songId;

        $playlist = Playlist::find($playlistId);
        $song = Song::where('song_Id', $songId)->first();

        if (!$playlist || !$song) {
            return response()->json(['message' => 'Playlist or Song not found'], 404);
        }

        $playlist->songs()->detach($song->id);

        $remaining = DB::table('playlist_song')->where('song_id', $song->id)->count();
        if ($remaining === 0) {
            $song->delete();
        }

        return response()->json(['message' => 'Song removed from playlist successfully']);
    }
}
