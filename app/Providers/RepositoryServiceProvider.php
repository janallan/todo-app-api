<?php

namespace App\Providers;

use App\Repositories\Task\Contracts\TaskInterface;
use App\Repositories\Task\TaskRepository;
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
        $this->tasksRepository();
    }

    /**
     * Bind \App\Repositories\User\UserRepository contracts
     */
    private function userRepository()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
    }

    /**
     * Bind \App\Repositories\User\TaskRepository contracts
     */
    private function tasksRepository()
    {
        $this->app->bind(TaskInterface::class, TaskRepository::class);
    }

}
