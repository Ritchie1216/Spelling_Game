<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Score;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function showGame()
    {
        return view('game');
    }

    public function startGame(Request $request)
    {
        $request->validate(['nickname' => 'required|string|max:255']);

        // Create or fetch the player based on nickname
        $player = Player::firstOrCreate(['nickname' => $request->nickname]);

        return response()->json(['player' => $player]);
    }

    public function submitScore(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'score' => 'required|integer',
        ]);

        $score = Score::create([
            'player_id' => $request->player_id,
            'score' => $request->score,
        ]);

        return response()->json(['score' => $score]);
    }

    public function leaderboard()
    {
        // 使用子查询获取每个玩家的最高分数记录
        $scores = Score::with('player')
            ->select('scores.*')
            ->join(\DB::raw('(
                SELECT player_id, MAX(score) as max_score
                FROM scores
                GROUP BY player_id
            ) as max_scores'), function($join) {
                $join->on('scores.player_id', '=', 'max_scores.player_id')
                     ->on('scores.score', '=', 'max_scores.max_score');
            })
            ->orderBy('score', 'desc')
            ->take(10)
            ->get();

        return view('leaderboard', compact('scores'));
    }
}