<?php

namespace App\Repositories\Task\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskInterface
{

    /**
     * Get User's Tasks
     *
     * @param int|string $userId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function getUserTasks(int|string $userId): LengthAwarePaginator;
}
