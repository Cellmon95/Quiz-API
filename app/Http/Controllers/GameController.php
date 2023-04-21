<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Question;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    function createGame(Request $request) {
        $numberOfQuestions = (int) $request->NOQ;

        if ($numberOfQuestions > DB::table('questions')->count()) {
            return ['msg' => 'Too many questions'];
        }

        if ($numberOfQuestions <= 0) {
            return ['msg' => 'Too few questions'];
        }
        
        try {
            $game = new Games;
            $game->user_id = DB::table('users')->where('api_token', $request->api_token)->pluck('id')[0];
            $game->save();
        } catch (Exception $e) {
            return ['msg' => 'Invalid API-Key'];
        }


        for ($i = 0; $i < $numberOfQuestions; $i++) {
            if ($i == 0) {
               $isCurrent = true;
            }
            else {
                $isCurrent = false;
            }
            DB::table('games_questions')->insert([
                'game_id' => $game->id,
                'question_id' => $i+1,
                'is_current' => $isCurrent,
            ]);
        }

        return ['game_id' => $game->id,];
    }

    function getQuestion(Request $request) {
        $game = DB::table('games')->where('id', $request->game_id);
        if ($game->pluck('user_id')->get(0) == null) {
            return ['msg' => 'Could not find game'];
        }


        if ($request->api_key == DB::table('users')->where('id', $game->pluck('user_id'))->pluck('api_token')->get(0)) {
            $response = DB::table('questions')
                ->select('the_question', 'alt_1', 'alt_2', 'alt_3', 'alt_4')
                ->join('games_questions', 'questions.id', '=', 'games_questions.question_id')
                ->join('games', 'games.id', '=', 'games_questions.game_id')
                ->where('games.id', $game->pluck('id'))
                ->where('games_questions.is_current', true)->get()[0];
        } else {
            return ['msg' => 'Wrong API-Key'];
        }

        return response()->json($response);
    }
}
