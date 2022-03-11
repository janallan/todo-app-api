<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseInterface
{
    /**
     * Get list of resource
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(): LengthAwarePaginator;

    /**
     * Create new resource
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model;

    /**
     * Get specified resource
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id): ?Model;

    /**
     * Update specified resource
     * @param int|string $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data): Model;

    /**
     * Delete specified resource
     * @param int|string $id
     * @return void
     */
    public function delete($id): void;
}
