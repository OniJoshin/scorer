<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'notes', 'started_at', 'completed_at'];

    protected $dates = ['started_at', 'completed_at'];

    protected $casts = [
        'notes' => 'string',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function results()
    {
        return $this->hasMany(GameSessionResult::class);
    }
}

