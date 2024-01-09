<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "Controller@contests");
Route::get('/contests/{contest}', "Controller@contest");
Route::get('/contests/{contest}/games', "Controller@games");
Route::get('/contests/{contest}/games/{game}', "Controller@game");
Route::get('/contests/{contest}/players', "Controller@players");
Route::prefix( "admin" )->name( "admin." )->group(
    function() {
        Route::get('/', "AuthController@index");
        Route::post('/auth', "AuthController@login");
        Route::get('/auth', "AuthController@logout");
        Route::resource( "/contests", ContestController::class )
            ->except( [ "show", "destroy" ] )
            ->names( "contests" );
        Route::resource( "/contests/{contests}/games", GameController::class )
            ->except( [ "show", "edit", "destroy" ] )
            ->names( "contests.games" );
        Route::resource( "/contests/{contests}/players", PlayerController::class )
            ->except( [ "show", "edit", "destroy" ] )
            ->names( "contests.players" );
    }
);
