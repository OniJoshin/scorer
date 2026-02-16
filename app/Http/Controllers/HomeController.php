<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\Score;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the dashboard home page
     */
    public function index(): View
    {
        // Get statistics
        $totalGames = Game::count();
        $totalPlayers = Player::count();
        $totalSessions = GameSession::count();
        
        // Get recent game sessions
        $recentSessions = GameSession::with(['game', 'results.player'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get most played game
        $mostPlayedGame = GameSession::select('game_id')
            ->selectRaw('count(*) as play_count')
            ->groupBy('game_id')
            ->orderByDesc('play_count')
            ->with('game')
            ->first();
        
        // Get top player (most game sessions participated)
        $topPlayer = Player::withCount('gameSessionResults')
            ->orderByDesc('game_session_results_count')
            ->first();
        
        return view('home', [
            'totalGames' => $totalGames,
            'totalPlayers' => $totalPlayers,
            'totalSessions' => $totalSessions,
            'recentSessions' => $recentSessions,
            'mostPlayedGame' => $mostPlayedGame,
            'topPlayer' => $topPlayer,
        ]);
    }
}
