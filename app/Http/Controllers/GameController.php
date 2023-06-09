<?php

namespace App\Http\Controllers;

use App\Models\Games;
use App\Models\Question;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $game->user_id = DB::table('users')->where('api_token', $request->api_key)->pluck('id')[0];
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

    function postAnswer(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();

        if($user === null){
            return ['error', 'No user with that api_token.'];
        }

        $currentGame = Games::where('user_id', $user->id)
        ->where('id', $request->game_id)->first();

        if ($currentGame == null) {
            return response(['msg' => 'Can\'t find game with that id.'], 401);
        }

        $currentGameQuestion = DB::table('games_questions')
        ->select('question_id')
        ->where('game_id', $currentGame->id)
        ->where('is_current', true)->first();

        if ($currentGameQuestion == null) {
            return response(['msg' => 'The game is done'], 400);
        }

        $currentQuestionId = $currentGameQuestion->question_id;

        $currentQuestion = Question::where('id', $currentQuestionId)->first();

        $gamesQuestionsIds = DB::table('games_questions')
        ->where('game_id', $currentGame->id)
        ->orderByDesc('id')->get();

        if ($currentQuestion->the_answer == $request->answer) {

            DB::table('games_questions')
            ->where('game_id', $currentGame->id)
            ->where('question_id', $currentQuestion->id)
            ->update(['result' => 'correct', 'is_current' => false]);

            $result = $this->iterateIsCurrent($currentQuestionId, $gamesQuestionsIds, $currentGame);

            //check if game is done
            if ($result) {
                $sumResult = DB::table('games_questions')
                ->where('game_id', $currentGame->id)
                ->select('result')->get();

                return ['result' => true, 'gameDone' => true, 'sumRes' => $sumResult];
            }
            else{
                return ['result' => true, 'gameDone' => false];
            }

            return ['result' => true];
        }

        DB::table('games_questions')
        ->where('game_id', $currentGame->id)
        ->where('question_id', $currentQuestion->id)
        ->update(['result' => 'incorrect', 'is_current' => false]);

        $result = $this->iterateIsCurrent($currentQuestionId, $gamesQuestionsIds, $currentGame);

        //check if game is done
        if ($result) {
            $sumResult = DB::table('games_questions')
            ->where('game_id', $currentGame->id)
            ->select('result')->get();

            return ['result' => false, 'gameDone' => true, 'sumRes' => $sumResult];
        }
        else{
            return ['result' => false, 'gameDone' => false];
        }
    }

    private function iterateIsCurrent($currentQuestionId, $gamesQuestionsIds, $currentGame)
    {
        $currentQuestionId++;
        if ($currentQuestionId > sizeof($gamesQuestionsIds)) {
            return true;
        }
        else{
            DB::table('games_questions')
            ->where('game_id', $currentGame->id)
            ->where('question_id', $currentQuestionId)
            ->update(['is_current' => true]);

            return false;
        }
    }


}
