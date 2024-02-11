<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Player;
use App\Models\Game;

class GameHasPlayer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'game_id',
        'table_number',
        "player_id",
        "rival_id",
        "integral",
        "sop",
        "rival_integral",
        "rival_sop",
        "is_initiative",
        "is_winner",
    ];
    public function info() {
        return $this->hasOne( Player::class, "id", "player_id" );
    }
    public function game() {
        return $this->hasOne( Player::class, "id", "player_id" );
    }

}
