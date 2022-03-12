<?php

namespace App\Repositories\Task;

use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\User\Contracts\OperatorInterface;
use App\Repositories\Task\Contracts\TaskInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskRepository extends BaseRepository implements TaskInterface
{
    /**
     * Create new BaseRepository instance
     */
    public function __construct()
    {
        parent::__construct(new Task());
    }

    /**
     * Get User's Tasks
     *
     * @param int|string $userId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function getUserTasks(int|string $userId): LengthAwarePaginator
    {
        QueryBuilder::for(Task::class)
            ->where('user_id', $userId)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->whereRaw("CONCAT(tasks.title, ' ', tasks.description) LIKE '%{$value}%'");
                }),
                AllowedFilter::callback('created', function (Builder $query, $value) {
                    if (isset($value['from'])  && isset($value['to'])) {
                        $query->whereRaw("DATE(driver_trips.created_at) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                        $query->whereRaw("DATE(number_of_trips.created_at) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                    }
                })
            ]);

        return Task::where('user_id', $userId)->paginate(15);
    }
}
