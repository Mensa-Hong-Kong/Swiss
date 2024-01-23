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
        Schema::table('games', function (Blueprint $table) {
            $table->dateTime( "id_card_number" )->nullable();
            $table->integer('initiative_point');
            $table->integer('gote_point');
            $table->integer('initiative_sop');
            $table->integer('gote_sop');
            $table->boolean( "initiative_check_in" )->default(0);
            $table->boolean( "gote_confirm_check_in" )->default(0);
            $table->boolean( "initiative_confirm" )->default(0);
            $table->boolean( "gote_confirm" )->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn( "id_card_number" )->nullable();
            $table->dropColumn('initiative_point');
            $table->dropColumn('gote_point');
            $table->dropColumn('initiative_sop');
            $table->dropColumn('gote_sop');
            $table->dropColumn( "initiative_check_in" )->default(0);
            $table->dropColumn( "gote_confirm_check_in" )->default(0);
            $table->dropColumn( "initiative_confirm" )->default(0);
            $table->dropColumn( "gote_confirm" )->default(0);
        });
    }
};
