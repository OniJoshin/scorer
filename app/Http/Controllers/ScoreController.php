<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function index()
    {
        $scores = Score::with('player', 'game')
            ->orderByDesc('played_at')
            ->orderByDesc('created_at')
            ->get();

        return view('scores.index', compact('scores'));
    }

    public function create()
    {
        $games = Game::orderBy('name')->get();
        $players = Player::orderBy('name')->get();
        return view('scores.create', compact('games', 'players'));
    }

    public function store(Request $request)
    {
        if ($request->has('entries')) {
            return $this->storeMatchResults($request);
        }

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'position' => 'required|integer|min:1',
            'played_at' => 'nullable|date',
        ]);

        $game = Game::findOrFail($request->game_id);
        $position = $request->position;
        $points = $game->position_points[$position] ?? 0;

        Score::create([
            'game_id' => $game->id,
            'player_id' => $request->player_id,
            'position' => $position,
            'points' => $points,
            'played_at' => $request->played_at ?: now(),
        ]);

        return redirect()->route('scores.index')->with('success', 'Score recorded!');
    }

    protected function storeMatchResults(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'played_at' => 'nullable|date',
            'entries' => 'required|array|min:2',
            'entries.*.player_id' => 'required|exists:players,id',
            'entries.*.position' => 'required|integer|min:1',
        ]);

        $entries = collect($validated['entries']);
        $duplicatePlayers = $entries->pluck('player_id')->duplicates();
        if ($duplicatePlayers->isNotEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['entries' => 'Each player can only appear once in a match result.']);
        }

        $duplicatePositions = $entries->pluck('position')->duplicates();
        if ($duplicatePositions->isNotEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['entries' => 'Each finishing position can only be used once.']);
        }

        $game = Game::findOrFail($validated['game_id']);
        $playedAt = $validated['played_at'] ?? now();

        DB::transaction(function () use ($entries, $game, $playedAt) {
            foreach ($entries as $entry) {
                $position = (int) $entry['position'];
                $points = $game->position_points[$position] ?? 0;

                Score::create([
                    'game_id' => $game->id,
                    'player_id' => $entry['player_id'],
                    'position' => $position,
                    'points' => $points,
                    'played_at' => $playedAt,
                ]);
            }
        });

        return redirect()->route('scores.index')->with('success', 'Match results recorded!');
    }

    public function edit(Score $score)
    {
        $games = Game::orderBy('name')->get();
        $players = Player::orderBy('name')->get();
        return view('scores.edit', compact('score', 'games', 'players'));
    }

    public function update(Request $request, Score $score)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_id' => 'required|exists:players,id',
            'position' => 'required|integer|min:1',
            'played_at' => 'nullable|date',
        ]);

        $game = Game::findOrFail($request->game_id);
        $position = $request->position;
        $points = $game->position_points[$position] ?? 0;

        $score->update([
            'game_id' => $game->id,
            'player_id' => $request->player_id,
            'position' => $position,
            'points' => $points,
            'played_at' => $request->played_at ?: $score->played_at ?: now(),
        ]);

        return redirect()->route('scores.index')->with('success', 'Score updated!');
    }

    public function destroy(Score $score)
    {
        $score->delete();
        return redirect()->route('scores.index')->with('success', 'Score deleted!');
    }
}
