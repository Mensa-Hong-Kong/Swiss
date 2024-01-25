<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\Game;

class GameController extends BaseController {
    protected $contest;
    protected $maxGameNumber;
    private function isLastGame( $request ) {
        $this->maxGameNumber = Game::where( "contest_id", $request[ "contest" ] )
            ->max( "game_number" );
        $this->contest = Contest::with([
                "games", "players" => function( $query ) {
                    if( $this->maxGameNumber % 2 == 0 ) {
                        $query->order( "id", "desc" );
                    } else {
                        $query->order( "id" );
                    }
                }
             ])->findOrFail( $request[ "contest" ] );
        if( $this->maxGameNumber == $this->contest[ "number_of_game" ] - 1 ) {
            return true;
        }
        return false;
    }
    public function __construct() {
        parent::__construct();
        $this->middleware(
            function( $request, $next ) {
                if( $this->isLastGame( $request ) ) {
                    return $next($request);
                }
                abort( 404 );
            }
        )->only( [ "create" ] );
        $this->middleware(
            function( $request, $next ) {
                if( $this->isLastGame( $request ) ) {
                    return $next($request);
                }
                return response()->json([
                    "type" => "msg",
                    "message" => "Just can create last game",
                ]);
            }
        )->only( [ "update" ] );
    }
    public function index() {
        // ...
    }
    public function create() {
        //...
    }
    public function store( Request $request ) {
        if( $this->isLastGame( $request ) ) {
            // ...
        } else {
            // ...
        }
    }
    public function update() {
        // ...
    }
}
