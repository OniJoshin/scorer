<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GameScoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::updateOrCreate(['name' => 'Ticket to Ride: Europe'], [
            'notes' => 'Simple position-based scoring setup.',
            'position_points' => [
                1 => 10,
                2 => 7,
                3 => 5,
                4 => 3,
            ],
        ]);
    }
}
