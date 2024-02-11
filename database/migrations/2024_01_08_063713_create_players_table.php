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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->bigInteger( "contest_id" );
            $table->bigInteger( "sn" )->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string( "name" );
            $table->string( "passport" )->nullable();
            $table->integer( "integral" )->default(0);
            $table->integer( "sop" )->default(0);
            $table->boolean( "withdraw" );
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
        Schema::dropIfExists('players');
    }
};
