<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Player;
use App\Models\Game;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->query('year');
        $gameId = $request->query('game_id');

        $playersQuery = Player::query()
            ->withCount(['scores as scores_count' => function ($query) use ($year, $gameId) {
                $query->when($year, function ($q) use ($year) {
                    $q->whereYear('played_at', $year);
                })->when($gameId, function ($q) use ($gameId) {
                    $q->where('game_id', $gameId);
                });
            }])
            ->withSum(['scores as total_points' => function ($query) use ($year, $gameId) {
                $query->when($year, function ($q) use ($year) {
                    $q->whereYear('played_at', $year);
                })->when($gameId, function ($q) use ($gameId) {
                    $q->where('game_id', $gameId);
                });
            }], 'points');

        $players = $playersQuery->orderByDesc('total_points')->get();

        $availableYears = Score::query()
            ->selectRaw('YEAR(played_at) as year')
            ->whereNotNull('played_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $availableGames = Game::orderBy('name')->get();

        return view('leaderboard.index', compact('players', 'year', 'gameId', 'availableYears', 'availableGames'));
    }
}
