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

Route::get('/', "HomeController@contests");
Route::get('/contests/{contest}', "HomeController@contest");
Route::get('/contests/{contest}/games', "HomeController@games");
Route::get('/contests/{contest}/games/{game}', "HomeController@game");
Route::get('/contests/{contest}/players', "HomeController@players");
Route::prefix( "player" )->name( "player." )->group(
    function() {
        Route::resource( "/profile", UserController::class )
            ->only( [ "index", "edit", "update" ] )
            ->names( "profile" );
        Route::get( "/qr-code", "ContestController@qrCode" );
        Route::resource( "/confirm-result", GameController::class )
            ->only( [ "edit", "update" ] )
            ->names( "confirm-result" );
        Route::get( "/check-in", "GameController@checkIn" )
            ->name( "check-in" );
    }
);
Route::prefix( "admin" )->name( "admin." )->group(
    function() {
        Route::get('/', "AuthController@index");
        Route::post('/auth', "AuthController@login");
        Route::get('/auth', "AuthController@logout");
        Route::resource( "/contests", AdminContestController::class )
            ->except( [ "show", "edit", "destroy" ] )
            ->names( "contests" );
        Route::resource( "/contests/{contests}/games", AdminGameController::class )
            ->except( [ "show", "edit", "destroy" ] )
            ->names( "contests.games" );
        Route::resource( "/contests/{contests}/players", AdminPlayerController::class )
            ->except( [ "show", "edit", "destroy" ] )
            ->names( "contests.players" );
    }
);
