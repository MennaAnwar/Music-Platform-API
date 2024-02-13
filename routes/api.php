<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeezerAPIController;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('show', [PassportAuthController::class, 'show']);
    Route::put('update', [PassportAuthController::class, 'update']);
    Route::delete('delete', [PassportAuthController::class, 'delete']);
});

Route::get('search', [DeezerAPIController::class, 'search']);
Route::get('artist', [DeezerAPIController::class, 'ArtistDetails']);
Route::get('genre', [DeezerAPIController::class, 'Genres']);
Route::get('chart', [DeezerAPIController::class, 'Charts']);
Route::get('track', [DeezerAPIController::class, 'Track']);


Route::post('/playlists', [PlaylistController::class, 'create']);
Route::get('/playlists', [PlaylistController::class, 'getPlaylists']);
Route::post('/addSong', [PlaylistController::class, 'addSong']);
Route::delete('/deletePlaylist', [PlaylistController::class, 'DeletePlaylist']);

Route::get('/playlist_songs',[SongController::class, 'getSongsForPlaylist']);
Route::delete('/deleteSong',[SongController::class, 'deleteSongFromPlaylist']);





