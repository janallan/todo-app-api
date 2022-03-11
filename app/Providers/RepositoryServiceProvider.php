<?php

namespace App\Providers;

use App\Repositories\User\Contracts\UserInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->userRepository();
    }

    /**
     * Bind \App\Repositories\User\UserRepository contracts
     */
    private function userRepository()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
    }
}
