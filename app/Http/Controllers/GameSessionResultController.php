<?php

namespace App\Http\Controllers;

use App\Models\GameSessionResult;
use Illuminate\Http\Request;

class GameSessionResultController extends Controller
{
    public function update(Request $request, GameSessionResult $result)
    {
        $request->validate([
            'position' => 'nullable|integer|min:1|max:10',
            'custom_score' => 'nullable|array',
        ]);

        $result->update([
            'position' => $request->position,
            'custom_score' => $request->custom_score,
        ]);

        return response()->json(['success' => true]);
    }
}
