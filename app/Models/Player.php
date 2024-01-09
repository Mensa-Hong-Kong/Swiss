<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Player extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contest_id',
        'sn',
        'name',
        'integral',
        'is_withdraw',
    ];
    public function initiativeGames() {
        return $this->hasMany( Game::class, "initiative_id" );
    }
    public function goteGames() {
        return $this->hasMany( Game::class, "gote_id" );
    }
    public function winnedGames() {
        return $this->hasMany( Game::class, "winner_id" );
    }
}
