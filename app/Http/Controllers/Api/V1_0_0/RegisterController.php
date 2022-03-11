<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Actions\V1_0_0\Auth\Login;
use App\Enums\NotificationMessages;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1_0_0\RegistrationRequest;
use App\Http\Resources\V1_0_0\LoginResponseResource;
use App\Http\Resources\V1_0_0\userResource;
use App\Repositories\User\Contracts\UserInterface;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /**
     * Create new Controller instance
     * @param \App\Repositories\User\Contracts\UserInterface $userInterface
     */
    public function __construct(private UserInterface $userInterface)
    {
    }

    /**
     * Register new user
     *
     * @param \App\Http\Requests\V1_0_0\RegistrationRequest $request
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function __invoke(
        RegistrationRequest $request,
    ) {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);

        $accessToken =  DB::transaction(function () use ($data, $request) {
            $user = $this->userInterface->create($data);

            $accessToken = (new Login)(['email' => $data['email'] , 'password' => $request->password]);

            return $accessToken;
        });

        return LoginResponseResource::make($accessToken);
    }
}
