<?php

namespace App\Repositories\User\Contracts;

use App\Models\User;

interface UserInterface
{
    /**
     * Get list of user
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all();

    /**
     * Create new user
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data);

    /**
     * Get specified user
     * @param int|string $id
     * @return \App\Models\User
     */
    public function find($id);

    /**
     * Update specified user
     * @param int|string $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update($id, array $data);

    /**
     * Delete specified user
     * @param int|string $id
     * @return void
     */
    public function delete($id): void;

    /**
     * Get User by phoneNumber
     * @param string $phoneNumber
     * @return \App\Models\User|null
     */
    public function getUserByPhoneNumber(string $phoneNumber): ?User;

    /**
     * Get User by email
     * @param string $email
     * @return \App\Models\User|null
     */
    public function getUserByEmail(string $email): ?User;

    /**
     * Get User by username (email or phoneNumber)
     * @param string $username
     * @return \App\Models\User|null
     */
    public function getUserByUsername(string $username): ?User;
}
