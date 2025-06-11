<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameSessionResult extends Model
{
    use HasFactory;

    protected $fillable = ['game_session_id', 'player_id', 'position', 'custom_score'];

    protected $casts = [
        'custom_score' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}

