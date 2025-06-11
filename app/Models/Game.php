<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['name', 'notes', 'played_at', 'position_points', 'scoring_rules'];

    protected $casts = [
        'position_points' => 'array',
        'played_at' => 'datetime',
        'scoring_rules' => 'array',
    ];

}
