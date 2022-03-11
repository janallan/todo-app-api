<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Actions\V1_0_0\Auth\Login;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1_0_0\LoginResponseResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Create new Controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login']);
    }

    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\V1_0_0\LoginResponseResource
     * @inject \App\Actions\V1_0_0\Auth\Login $login
     * @creator Jan Allan Verano
     */
    public function login(Request $request, Login $login)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $token = $login($request->all());

        return LoginResponseResource::make($token);
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function logout()
    {

        $user = request()->user()->tokens()->delete();

        return response()->json([], 204);
    }

}
