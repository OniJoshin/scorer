<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        $scores = Score::with('player', 'game')
            ->orderByDesc('created_at')
            ->get();

        return view('scores.index', compact('scores'));
    }

    public function create()
    {
        $games = Game::orderByDesc('played_at')->get();
        $players = Player::orderBy('name')->get();
        return view('scores.create', compact('games', 'players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'position' => 'required|integer|min:1',
        ]);

        $game = Game::findOrFail($request->game_id);
        $position = $request->position;
        $points = $game->position_points[$position] ?? 0;

        Score::create([
            'game_id' => $game->id,
            'player_id' => $request->player_id,
            'position' => $position,
            'points' => $points,
        ]);

        return redirect()->route('scores.index')->with('success', 'Score recorded!');
    }

    public function edit(Score $score)
    {
        $games = Game::orderByDesc('played_at')->get();
        $players = Player::orderBy('name')->get();
        return view('scores.edit', compact('score', 'games', 'players'));
    }

    public function update(Request $request, Score $score)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'position' => 'required|integer|min:1',
        ]);

        $game = Game::findOrFail($request->game_id);
        $position = $request->position;
        $points = $game->position_points[$position] ?? 0;

        $score->update([
            'game_id' => $game->id,
            'player_id' => $request->player_id,
            'position' => $position,
            'points' => $points,
        ]);

        return redirect()->route('scores.index')->with('success', 'Score updated!');
    }

    public function destroy(Score $score)
    {
        $score->delete();
        return redirect()->route('scores.index')->with('success', 'Score deleted!');
    }
}
