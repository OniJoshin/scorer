<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{

    protected $fillable = ['name'];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
    
    public function gameSessionResults()
    {
        return $this->hasMany(GameSessionResult::class);
    }

}
