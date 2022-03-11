<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\Contracts\OperatorInterface;
use App\Repositories\User\Contracts\UserInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * Create new BaseRepository instance
     */
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * Get User by phoneNumber
     * @param string $phoneNumber
     * @return \App\Models\User|null
     */
    public function getUserByPhoneNumber(string $phoneNumber): ?User
    {
        return User::wherePhoneNumber($phoneNumber)->first();
    }

    /**
     * Get User by email
     * @param string $email
     * @return \App\Models\User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }

    /**
    * Get User by username (email or phoneNumber)
    * @param string $username
    * @return \App\Models\User|null
    */
    public function getUserByUsername(string $username): ?User
    {
        return User::wherePhoneNumber($username)->orWhere('email', $username)->first();
    }
}
