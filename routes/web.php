<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\GameSessionController;

Route::get('/', function () {
    return view('home');
});



Route::resource('players', PlayerController::class);
Route::resource('games', GameController::class);
Route::resource('scores', ScoreController::class);




Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');




Route::prefix('game-sessions')->name('game_sessions.')->group(function () {
    Route::get('/', [GameSessionController::class, 'index'])->name('index');               // list sessions
    Route::get('/create', [GameSessionController::class, 'create'])->name('create');       // start new session
    Route::post('/', [GameSessionController::class, 'store'])->name('store');              // save new session
    Route::get('/{session}', [GameSessionController::class, 'show'])->name('show');        // editable session page
    Route::patch('/{session}/complete', [GameSessionController::class, 'complete'])->name('complete'); // finish session
});

Route::patch('/game-session-results/{result}', [\App\Http\Controllers\GameSessionResultController::class, 'update'])
    ->name('game_session_results.update');

Route::post('/game-sessions/{session}/update-results', [GameSessionController::class, 'updateResults'])
    ->name('game_sessions.update_results');




