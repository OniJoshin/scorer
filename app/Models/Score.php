<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['game_id', 'player_id', 'position', 'points', 'played_at'];

    protected $casts = [
        'played_at' => 'datetime',
    ];

    public function player()
    {
        return $this->belongsTo(\App\Models\Player::class);
    }

    public function game()
    {
        return $this->belongsTo(\App\Models\Game::class);
    }

}
