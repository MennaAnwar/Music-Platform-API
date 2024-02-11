<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeezerAPIController;
use App\Http\Controllers\PlaylistController;

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

Route::get('search', [DeezerAPIController::class, 'search']);
Route::get('artist', [DeezerAPIController::class, 'ArtistDetails']);
Route::get('genre', [DeezerAPIController::class, 'Genres']);
Route::get('chart', [DeezerAPIController::class, 'Charts']);

Route::post('/playlists', [PlaylistController::class, 'create']);
Route::get('/playlists', [PlaylistController::class, 'getPlaylists']);






