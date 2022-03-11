<?php

namespace App\Actions\V1_0_0\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login
{

    /**
     * Login
     *
     * @param array $credentials
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function __invoke(array $credentials)
    {
        $user = $this->getUser($credentials);

        $user->tokens()->delete();

        $token = $user->createToken(request()->userAgent());

        return [
            'token' => $token->plainTextToken,
            'user' => $user,
        ];
    }

    /**
     * Get User Model
     *
     * @param array $credentials
     * @return \App\Models\User
     * @creator Jan Allan Verano
     */
    private function getUser(array $credentials): User
    {

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException();
        }

        return $user;

    }

}
