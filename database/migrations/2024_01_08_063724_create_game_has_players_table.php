<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_has_players', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( "game_id" );
            $table->integer( "game_number" );
            $table->bigInteger( "player_id" );
            $table->bigInteger( "rival_id" );
            $table->integer( "integral" );
            $table->integer( "sop" );
            $table->integer( "rival_integral" );
            $table->integer( "rival_sop" );
            $table->boolean( "is_initiative" );
            $table->boolean( "is_winner" );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};
