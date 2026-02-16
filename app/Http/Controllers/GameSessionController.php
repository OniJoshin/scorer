<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\Score;
use App\Support\SessionHelpers\SessionHelperFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameSessionController extends Controller
{
    public function __construct(protected SessionHelperFactory $helperFactory)
    {
    }

    public function index()
    {
        $sessions = GameSession::with('game')->latest()->get();
        return view('game_sessions.index', compact('sessions'));
    }

    public function create()
    {
        $games = Game::orderBy('name')->get();
        $players = Player::orderBy('name')->get();
        $helperTypes = collect($this->helperFactory->all())
            ->mapWithKeys(fn ($helper, $key) => [
                $key => [
                    'label' => $helper->label(),
                    'description' => $helper->description(),
                    'default_target' => $helper->defaultTargetScore(),
                ],
            ])
            ->all();

        return view('game_sessions.create', compact('games', 'players', 'helperTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'player_ids' => 'required|array|min:2',
            'player_ids.*' => 'exists:players,id',
            'helper_type' => 'required|in:round_points,flip7',
            'target_score' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $helper = $this->helperFactory->resolve($request->helper_type);
        $defaultTarget = $helper->defaultTargetScore();

        $session = GameSession::create([
            'game_id' => $request->game_id,
            'notes' => $request->notes,
            'helper_type' => $request->helper_type,
            'target_score' => $request->filled('target_score') ? (int) $request->target_score : $defaultTarget,
            'started_at' => now(),
        ]);

        foreach ($request->player_ids as $playerId) {
            $session->results()->create(['player_id' => $playerId]);
        }

        return redirect()->route('game_sessions.show', $session);
    }

    public function show(GameSession $session)
    {
        $session->load(['game', 'results.player']);

        $helperInstance = $this->helperFactory->resolve($session->helper_type);
        $helper = [
            'key' => $helperInstance->key(),
            'label' => $helperInstance->label(),
            'description' => $helperInstance->description(),
            'default_target' => $helperInstance->defaultTargetScore(),
        ];

        $scorebookState = [];
        foreach ($session->results as $result) {
            $rounds = $result->custom_score['rounds'] ?? [];
            $total = (int) ($result->custom_score['total'] ?? array_sum(is_array($rounds) ? $rounds : []));

            $scorebookState[$result->id] = [
                'rounds' => is_array($rounds) ? array_values($rounds) : [],
                'total' => $total,
            ];
        }

        $canComplete = $helperInstance->canComplete($session->results, $session->target_score);

        return view('game_sessions.show', compact('session', 'helper', 'scorebookState', 'canComplete'));
    }

    public function complete(GameSession $session)
    {
        if ($session->completed_at) {
            return redirect()->route('game_sessions.show', $session)
                ->with('success', 'Session is already complete.');
        }

        $session->load(['game', 'results.player']);

        $helper = $this->helperFactory->resolve($session->helper_type);
        if (!$helper->canComplete($session->results, $session->target_score)) {
            return redirect()->route('game_sessions.show', $session)
                ->with('error', 'Session cannot be completed yet. Target score has not been reached.');
        }

        DB::transaction(function () use ($session) {
            $rankedResults = $session->results
                ->sortByDesc(fn ($result) => (int) ($result->custom_score['total'] ?? 0))
                ->values();

            foreach ($rankedResults as $index => $result) {
                $position = $index + 1;
                $points = (int) (($session->game->position_points ?? [])[$position] ?? 0);

                $result->update([
                    'position' => $position,
                    'custom_score' => array_merge($result->custom_score ?? [], [
                        'position' => $position,
                        'leaderboard_points' => $points,
                    ]),
                ]);

                Score::create([
                    'game_id' => $session->game_id,
                    'player_id' => $result->player_id,
                    'position' => $position,
                    'points' => $points,
                    'played_at' => $session->started_at ?? $session->created_at ?? now(),
                ]);
            }

            $session->update(['completed_at' => now()]);
        });

        return redirect()->route('game_sessions.index')->with('success', 'Session completed!');
    }

    public function updateResults(Request $request, GameSession $session)
    {
        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.total' => 'nullable|integer|min:0',
            'scores.*.rounds_json' => 'nullable|string',
            'target_score' => 'nullable|integer|min:1',
        ]);

        $session->load('results');

        $session->update([
            'target_score' => $validated['target_score'] ?? $session->target_score,
        ]);

        foreach ($session->results as $result) {
            $scoreData = $validated['scores'][$result->id] ?? [];

            $rounds = [];
            if (!empty($scoreData['rounds_json'])) {
                $decoded = json_decode($scoreData['rounds_json'], true);
                if (is_array($decoded)) {
                    $rounds = array_values(array_map(static fn ($value) => (int) $value, $decoded));
                }
            }

            $total = isset($scoreData['total']) ? (int) $scoreData['total'] : array_sum($rounds);

            $result->update([
                'position' => null,
                'custom_score' => [
                    'mode' => 'scorebook',
                    'helper' => $session->helper_type,
                    'rounds' => $rounds,
                    'total' => $total,
                ],
            ]);
        }

        return redirect()->route('game_sessions.show', $session)
            ->with('success', 'Scores updated successfully.');
    }
}

