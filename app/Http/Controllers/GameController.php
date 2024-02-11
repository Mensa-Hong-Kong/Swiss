<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Models\Game;

class GameController extends BaseController {
    protected $contest;
    protected $maxGameNumber;
    private function isLastGame( $request ) {
        $maxGameNumber = Game::where( "contest_id", $request[ "contest" ] )
            ->max( "game_number" );
        $this->maxGameNumber = $maxGameNumber;
        if( is_null( $maxGameNumber ) ) {
            $contest = Contest::with( "players" );
        } else {
            $contest = Contest::with([
                "games" => function ( $query ) use( $maxGameNumber ) {
                    $query->with([
                        "players" => function( $query ) use($maxGameNumber) {
                            $query->order( "is_winner" );
                            if( $maxGameNumber % 2 == 0 ) {
                                $query->order( "sn", "desc" );
                            } else {
                                $query->order( "sn" );
                            }
                        }
                    ])
                    ->orderBy( "game_number" );
                }
            ]);
        }
        $this->contest = $contest->findOrFail( $request[ "contest" ] );
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
        if( is_null() ) {
            // first game
            $playerIDs = $this->contest[ "players" ]
                ->pluck( "id" )
                ->toArray();
            $totalPlayer = count( $this->contest[ "players" ] );
            if( $totalPlayer % 2 == 1 ) {
                $create = [
                    "contest_id" => $this->contest[ "id" ],
                    "name" => "bye",
                ];
                $bye = Player::create( $create );
                $playerIDs[] = $bye[ "id" ];
                ++$totalPlayer;
            }
            $reIndex = range( 1, $totalPlayer );
            $playerIDs = array_combine( $reIndex, array_values( shuffle( $playerIDs ) ) );
            $create = [
                "contest_id" => $this->contest[ "id" ],
                "game_number" => "1",
            ];
            $game = Game::create( $create );
            foreach( $playerIDs as $key => $playerID ) {
                Player::where( "id", $playerID )
                    ->update( [ "sn", $key ] );
            }
            $insert = [];
            foreach( range( 1, $playerIDs / 2 ) as $tableNumber ) {
                $insert[] = [
                    'game_id' => $game[ "id" ],
                    'table_number' => $tableNumber,
                    "player_id" => $playerIDs[ 2 * $tableNumber - 1 ],
                    "rival_id" => $playerIDs[ 2 * $tableNumber ],
                    "integral" => 0,
                    "sop" => 0,
                    "rival_integral" => 0,
                    "rival_sop" => 0,
                    "is_initiative" => 1,
                ];
                $insert[] = [
                    'game_id' => $game[ "id" ],
                    'table_number' => $tableNumber,
                    "player_id" => $playerIDs[ 2 * $tableNumber ],
                    "rival_id" => $playerIDs[ 2 * $tableNumber - 1 ],
                    "integral" => 0,
                    "sop" => 0,
                    "rival_integral" => 0,
                    "rival_sop" => 0,
                    "is_initiative" => 0,
                ];
            }
            GameHasPlayer::insert( $insert );
            return [ "type" => "success" ];
        } else if( $this->isLastGame( $request ) ) {
            // last game
        } else {
            $playerIDs = $this->contest[ "games" ][0][ "player" ]
                ->pluck( "player_id" )
                ->toArray();
            $playersBlackList = array_fill_keys( $playerIDs, [] );
            $playersInitiativeTime = array_fill_keys( $playerIDs, 0 );
            foreach( $this->contest[ "games" ] as $game ) {
                foreach( $game[ "players" ] as $player ) {
                    $playersBlackList[ $player[ "id" ] ][] = $player[ "rival_id" ];
                    if( $player[ "is_initiative" ] ) {
                        $playersInitiativeTime[ $player[ "id" ] ] += 1;
                    }
                }
            }
            $keys = array_fill_keys( $playerIDs, 0 );
            $base = array_diff( $playerIDs, $playersBlackList[ $playerIDs[0] ] );
            $totalPlayer = count( $keys );
            $shouldHaveTable = $totalPlayer/2;
            $time = 0;
            while( true ) {
                $newPlayersBlackList = $playersBlackList;
                $tables = [];
                $countTable = 0;
                foreach( $newPlayersBlackList as $playerID => $blackList ) {
                    $playerRivals = array_diff( array_keys( $newPlayersBlackList ), $blackList );
                    if( count( $playerRivals ) > 0 ) {
                        $tables[ $playerID ] = array_values( $playerRivals )[ $keys[ $playerID ] ];
                        unset( $newPlayersBlackList[ $playerID ] );
                        ++$countTable;
                    } else {
                        break;
                    }
                }
                if( count( $tables ) == $shouldHaveTable ) {
                    break;
                }
                ++$time;
                if( $time >= $base * $shouldHaveTable ) {
                    return [
                        "type" => "msg",
                        "message" => "have no not repeating combinations",
                    ];
                }
                // time to base by keys array
                $baseTime = 0;
                $number = $time;
                while( $number > $base ) {
                    $keys[ $playerIDs[ $time ] ] = $number % $base;
                    $number = floor( $number % $base );
                    $baseTime++;
                }
                $keys[ $playerIDs[ $baseTime ] ] = $number;
            }
            $players = Player::select( "id", "integral", "sop" )
                ->get()
                ->keyBy( "id" )
                ->toArray();
            $create = [
                "contest_id" => $this->contest[ "id" ],
                "game_number" => $this->maxGameNumber,
            ];
            $game = Game::create( $create );
            $insert = [];
            foreach( $tables as $initiativePlayerID => $gotePlayerID ) {
                if( $playersInitiativeTime[ $initiativePlayerID ] > $playersInitiativeTime[ $gotePlayerID ] ) {
                    [ $gotePlayerID, $initiativePlayerID ] = [ $initiativePlayerID, $gotePlayerID ];
                }
                $insert[] = [
                    'game_id' => $game[ "id" ],
                    'table_number' => $tableNumber,
                    "player_id" => $initiativePlayerID,
                    "rival_id" => $gotePlayerID,
                    "integral" => $players[ $initiativePlayerID ][ "integral" ],
                    "sop" => $players[ $initiativePlayerID ][ "sop" ],
                    "rival_integral" => $players[ $gotePlayerID ][ "integral" ],
                    "rival_sop" => $players[ $gotePlayerID ][ "sop" ],
                    "is_initiative" => 1,
                ];
                $insert[] = [
                    'game_id' => $game[ "id" ],
                    'table_number' => $tableNumber,
                    "player_id" => $gotePlayerID,
                    "rival_id" => $initiativePlayerID,
                    "integral" => $players[ $gotePlayerID ][ "integral" ],
                    "sop" => $players[ $gotePlayerID ][ "sop" ],
                    "rival_integral" => $players[ $initiativePlayerID ][ "integral" ],
                    "rival_sop" => $players[ $initiativePlayerID ][ "sop" ],
                    "is_initiative" => 0,
                ];
            }
            GameHasPlayer::insert( $insert );
            return [ "type" => "success" ];
        }
    }
    public function update() {
        // ...
    }
}
