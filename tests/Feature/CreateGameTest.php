<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateGameTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_game_returns_game_id(): void
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
            [
                'the_question' => 'Är det blött?',
                'alt_1' => 'Det är blött',
                'alt_2' => 'ja',
                'alt_3' => 'nej',
                'alt_4' => 'kanske',
                'the_answer' => 4
            ],
            [
                'the_question' => 'Är det blekt?',
                'alt_1' => 'Det är blekt',
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

        $response = $this->post('/api/games', [
            'api_key' => $apiKey,
            'NOQ' => '3',
        ]);

        $response->assertStatus(200)->assertJson(['game_id' => '1']);
    }

    public function test_return_msg_if_too_many_questions(): void
    {

        $apiKey = $this->post('/api/register', [
            'name' => 'testUser',
            'email' => 'test@email',
            'password' => 'test',
        ])['api_key'];

        $response = $this->post('/api/games', [
            'api_key' => $apiKey,
            'NOQ' => '3',
        ]);

        $response->assertStatus(200)->assertJson(['msg' => 'Too many questions']);
    }

    public function test_return_msg_if_too_few_questions(): void
    {

        $apiKey = $this->post('/api/register', [
            'name' => 'testUser',
            'email' => 'test@email',
            'password' => 'test',
        ])['api_key'];

        $response = $this->post('/api/games', [
            'api_key' => $apiKey,
            'NOQ' => '0',
        ]);

        $response->assertStatus(200)->assertJson(['msg' => 'Too few questions']);
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
            [
                'the_question' => 'Är det blött?',
                'alt_1' => 'Det är blött',
                'alt_2' => 'ja',
                'alt_3' => 'nej',
                'alt_4' => 'kanske',
                'the_answer' => 4
            ],
            [
                'the_question' => 'Är det blekt?',
                'alt_1' => 'Det är blekt',
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

        $response = $this->post('/api/games', [
            'api_key' => $apiKey . 'e',
            'NOQ' => '3',
        ]);

        $response->assertStatus(200)->assertJson(['msg' => 'Invalid API-Key']);
    }
}
