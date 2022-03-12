<?php

namespace App\Repositories\Tag\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface TagInterface
{

    /**
     * Get User's Tags
     *
     * @param int|string $userId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function getUserTags(int|string $userId): LengthAwarePaginator;
}
