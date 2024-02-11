<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
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
    
}
