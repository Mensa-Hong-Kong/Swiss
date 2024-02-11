<?php

namespace App\Http\Controllers;
use App\Models\Contest;
use App\Models\Player;
use App\Http\Requests\Admin\PlayerRequest;

use App\Http\Controllers\Controller as BaseController;

class PlayerController extends BaseController {
    public function index( Contest $contest ) {
        $players = Player::where( "contest_id", $contest[ "id" ] )
            ->sortable()
            ->paginate();
        return view( "admin.players.index" )
            ->with( "players", $players );
    }
    public function create( Contest $contest ) {
        return view( "admin.players.create" )
            ->with( "contest", $contest );
    }
    public function store( PlayerRequest $request, Contest $contest ) {
        $create = [
            'contest_id' => $contest[ "id" ],
            'name' => $request[ "name" ],
            "id_card_number" => $request[ "id_card_number" ],
        ];
        Player::create( $create );
    }
    public function update( PlayerRequest $request, Contest $contest, Player $player ) {
        $create = [
            'contest_id' => $contest[ "id" ],
            'name' => $request[ "name" ],
            "id_card_number" => $request[ "id_card_number" ],
            'is_withdraw' => $request[ "id_card_number" ],
        ];
        $player->update( $create );
    }
}
