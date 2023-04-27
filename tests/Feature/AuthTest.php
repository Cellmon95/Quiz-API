<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_register(): void{
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'test@yrgo.se',
            'password' => 'test'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'api_key'
        ]);


    }

    public function test_login_wrong_user(): void
    {
        $response = $this->post('/api/login', [
            'name' => 'John No More',
            'password' => '123'
        ]);

        $response->assertStatus(302);
        $response->assertJson([
            'msg' => 'login failed. Wrong username or password.'
        ]);

        /*
        $response->assertJsonStructure([
            'api_key'
        ]);*/
    }

    public function test_login_existing_user(): void{

        $user = new User();
        $user->name = 'John Doe';
        $user->email = 'test@yrgo.se';
        $user->password = 'test';
        $user->save();

        $response = $this->post('/api/login', [
            'name' => 'John Doe',
            'password' => 'test'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'api_key'
        ]);

    }
}