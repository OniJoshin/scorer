<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::orderByDesc('played_at')->get();
        return view('games.index', compact('games'));
    }

    public function create()
    {
        return view('games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'scoring_rules' => 'nullable|string',
            'played_at' => 'nullable|date',
            'position_points' => 'nullable|array',
            'position_points.*' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'notes', 'played_at']);
        $data['position_points'] = $request->filled('position_points') ? $request->position_points : null;

        $data['scoring_rules'] = $request->filled('scoring_rules')
            ? json_decode($request->scoring_rules, true)
            : null;


        Game::create($data);
        return redirect()->route('games.index')->with('success', 'Game created!');
    }

    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'scoring_rules' => 'nullable|string',
            'played_at' => 'nullable|date',
            'position_points' => 'nullable|array',
            'position_points.*' => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name', 'notes', 'played_at']);
        $data['position_points'] = $request->filled('position_points') ? $request->position_points : null;

        $data['scoring_rules'] = $request->filled('scoring_rules')
            ? json_decode($request->scoring_rules, true)
            : null;

        $game->update($data);
        return redirect()->route('games.index')->with('success', 'Game updated!');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Game deleted!');
    }
}
