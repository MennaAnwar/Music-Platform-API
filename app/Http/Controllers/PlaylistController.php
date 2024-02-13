<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PlaylistController extends Controller
{
    public function create(Request $request){
        // Ensure there's an authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->user_id = $user->id; 
        $playlist->save();

        $playlists = $user->playlists; 

        return response()->json($playlists, 201);
    }

    public function getPlaylists(){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlists = $user->playlists;
        return response()->json($playlists);
    }

    public function addSong(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlist = Playlist::find($request->playlistId);
        if (!$playlist) {
            return response()->json(['message' => 'Playlist not found'], 404);
        }

        $song = Song::where('song_Id', $request->songId)->first();
        if (!$song) {
            $song = new Song;
            $song->song_Id = $request->songId;
            $song->save();
        }

        if (!$playlist->songs()->where('songs.song_Id', $song->song_Id)->exists()) {
            $playlist->songs()->attach($song);
            return response()->json(['message' => 'Song added to playlist successfully', 'song' => $song]);
        } else {
            return response()->json(['message' => 'Song already exists in the playlist'], 409); 
        }
    }

    public function DeletePlaylist(Request $request){
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Not authenticated'], 401); 
        }

        $playlist = Playlist::find($request->playlistId);
        if (!$playlist) {
            return response()->json(['message' => 'Playlist not found'], 404);
        }

        $songs = $playlist->songs;

        $playlist->songs()->detach();

        $playlist->delete();

        foreach ($songs as $song) {
            if ($song->playlists()->count() == 0) {
                $song->delete();
            }
        }

        $remainingPlaylists = Playlist::all();

        return response()->json([
            'message' => 'Playlist and its exclusive songs deleted successfully',
            'remainingPlaylists' => $remainingPlaylists
        ]);
    }
        
}
