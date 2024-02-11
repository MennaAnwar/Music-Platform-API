<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function create(Request $request)
    {
        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->save();

        $playlists = Playlist::all();

        return response()->json($playlists, 201);
    }

    public function getPlaylists()
    {
        $playlists = Playlist::all();
        return response()->json($playlists);
    }

    public function addSong(Request $request){

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
            return response()->json(['message' => 'Song already exists in the playlist'], 409); // 409 Conflict
        }
    }
        
}
