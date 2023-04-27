<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json(['api_key' => $user->api_token], 201);
    }

    public function login(Request $request)
    {

        $loginData = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($loginData)) {
            $user = Auth::user();
            return response()->json(['api_key' => $user->api_token]);
        }

        return response()->json(['msg' => 'login failed. Wrong username or password.'], 302);
    }
}
