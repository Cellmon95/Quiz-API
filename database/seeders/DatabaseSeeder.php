<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        DB::table('games')->insert([
            [
                'id' => 1,
                'user_id' => 1,
            ]
        ]);

        DB::table('questions')->insert([
            [
                'id' => 1,
                'the_question' => 'Är det blått?',
                'alt_1' => 'Det är blått',
                'alt_2' => 'ja',
                'alt_3' => 'nej',
                'alt_4' => 'kanske',
                'the_answer' => 4
            ]
        ]);

        DB::table('games_questions')->insert([
            [
                'id' => 1,
                'question_id' => 1,
                'game_id' => 1,
                'result' => 'undecided',
                'is_current' => true,

            ]
        ]);
    }
}
