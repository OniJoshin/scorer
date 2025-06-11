<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'notes' => 'Build train routes across Europe. Bonus points for longest route and unused stations.',
            'scoring_rules' => json_encode([
                'fields' => [
                    ['key' => 'routes', 'label' => 'Completed Routes', 'type' => 'number'],
                    ['key' => 'longest_route', 'label' => 'Longest Route Bonus', 'type' => 'checkbox', 'points' => 10],
                    ['key' => 'stations_left', 'label' => 'Unused Stations', 'type' => 'number', 'multiplier' => 4],
                ],
                'auto_rank' => 'sum'
            ])
        ]);
    }
}
