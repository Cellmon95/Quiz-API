<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/question', function (Request $request) {
    $game = DB::table('games')->where('id', $request->game_id);
    if ($game->pluck('user_id')->get(0) == null) {
        return "Could not find game";
    }


    if ($request->api_key == DB::table('users')->where('id', $game->pluck('user_id'))->pluck('remember_token')->get(0)) {
        $question = DB::table('questions')
            ->select('the_question', 'alt_1', 'alt_2', 'alt_3', 'alt_4')
            ->join('games_questions', 'questions.id', '=', 'games_questions.question_id')
            ->join('games', 'games.id', '=', 'games_questions.game_id')
            ->where('games.id', $game->pluck('id'))
            ->where('games_questions.is_current', true)->get()[0];
    } else {
        return "Wrong API-Key";
    }

    $response = $question;
    return response()->json($response);

});



Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

