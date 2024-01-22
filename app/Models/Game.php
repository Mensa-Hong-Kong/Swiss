<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Player;

class Game extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contest_id',
        'game_number',
        'initiative_id',
        'gote_id',
        'winner_id',
    ];
    public function initiative() {
        return $this->hasOne( Player::class, "id", "initiative_id" );
    }
    public function gote() {
        return $this->hasOne( Player::class, "id", "initiative_id" );
    }
    public function winner() {
        return $this->hasOne( Player::class, "id", "initiative_id" );
    }

}
