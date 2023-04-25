<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GetQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_question_and_alternatives(): void
    {

        DB::table('questions')->insert([
            [
                'the_question' => 'Är det blått?',
                'alt_1' => 'Det är blått',
                'alt_2' => 'ja',
                'alt_3' => 'nej',
                'alt_4' => 'kanske',
                'the_answer' => 4
            ],
        ]);

        $apiKey = $this->post('/api/register', [
            'name' => 'testUser',
            'email' => 'test@email',
            'password' => 'test',
        ])['api_key'];

        $gameId = $this->post('/api/games', [
            'api_key' => $apiKey,
            'NOQ' => '1',
        ])['game_id'];

        $response = $this->get('/api/question?game_id=' . $gameId . '&api_key=' . $apiKey);

        $response->assertStatus(200)->assertJson([
            'the_question' => 'Är det blått?',
            'alt_1' => 'Det är blått',
            'alt_2' => 'ja',
            'alt_3' => 'nej',
            'alt_4' => 'kanske',
        ]);
    }

    public function test_returns_msg_if_invalid_game_id(): void
    {

        $apiKey = $this->post('/api/register', [
            'name' => 'testUser',
            'email' => 'test@email',
            'password' => 'test',
        ])['api_key'];

        $response = $this->get('/api/question?game_id=' . '' . '&api_key=' . $apiKey);

        $response->assertStatus(200)->assertJson([
            'msg' => 'Could not find game',
        ]);
    }

    public function test_returns_msg_if_invalid_api_key(): void
    {

        DB::table('questions')->insert([
            [
                'the_question' => 'Är det blått?',
                'alt_1' => 'Det är blått',
                'alt_2' => 'ja',
                'alt_3' => 'nej',
                'alt_4' => 'kanske',
                'the_answer' => 4
            ],
        ]);

        $apiKey = $this->post('/api/register', [
            'name' => 'testUser',
            'email' => 'test@email',
            'password' => 'test',
        ])['api_key'];

        $gameId = $this->post('/api/games', [
            'api_key' => $apiKey,
            'NOQ' => '1',
        ])['game_id'];

        $response = $this->get('/api/question?game_id=' . $gameId . '&api_key=' . '');

        $response->assertStatus(200)->assertJson([
            'msg' => 'Wrong API-Key',
        ]);
    }
}
