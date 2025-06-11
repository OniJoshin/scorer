<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Player;
use Illuminate\Http\Request;

class GameSessionController extends Controller
{
    public function index()
    {
        $sessions = GameSession::with('game')->latest()->get();
        return view('game_sessions.index', compact('sessions'));
    }

    public function create()
    {
        $games = Game::orderBy('name')->get();
        $players = Player::orderBy('name')->get();
        return view('game_sessions.create', compact('games', 'players'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_ids' => 'required|array|min:2',
            'player_ids.*' => 'exists:players,id',
        ]);

        $session = GameSession::create([
            'game_id' => $request->game_id,
            'notes' => $request->notes,
        ]);

        foreach ($request->player_ids as $playerId) {
            $session->results()->create(['player_id' => $playerId]);
        }

        return redirect()->route('game_sessions.show', $session);
    }

    public function show(GameSession $session)
    {
        $session->load(['game', 'results.player']);

        $scoringRules = $session->game->scoring_rules ?? [];

        return view('game_sessions.show', compact('session', 'scoringRules'));
    }

    public function complete(GameSession $session)
    {
        $session->update(['completed_at' => now()]);
        return redirect()->route('game_sessions.index')->with('success', 'Session completed!');
    }

    public function updateResults(Request $request, GameSession $session)
    {
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.data' => 'required|array',
            'scores.*.total' => 'nullable|integer',
        ]);

        foreach ($validated['scores'] as $resultId => $scoreData) {
            $session->results()->where('id', $resultId)->update([
                'custom_score' => $scoreData['data'],
                'total' => $scoreData['total'] ?? null,
            ]);
        }

        return redirect()->route('game_sessions.show', $session)
            ->with('success', 'Scores updated successfully.');
    }

}

