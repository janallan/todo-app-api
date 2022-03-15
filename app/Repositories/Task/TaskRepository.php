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
        return QueryBuilder::for(Task::class)
            ->where('user_id', $userId)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->whereRaw("CONCAT(tasks.title, ' ', tasks.description) LIKE '%{$value}%'");
                }),
                AllowedFilter::callback('prioritization', function (Builder $query, $value) {
                    $query->whereRaw("prioritization = '{$value}'");
                }),
                AllowedFilter::callback('completed', function (Builder $query, $value) {
                    if (isset($value['from'])  && isset($value['to'])) {
                        $query->whereRaw("DATE(tasks.completed_at) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                    }
                }),
                AllowedFilter::callback('due', function (Builder $query, $value) {
                    if (isset($value['from'])  && isset($value['to'])) {
                        $query->whereRaw("DATE(tasks.due_date) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                    }
                }),
                AllowedFilter::callback('archived', function (Builder $query, $value) {
                    if (isset($value['from'])  && isset($value['to'])) {
                        $query->whereRaw("DATE(tasks.archived_at) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                    }
                }),
                AllowedFilter::callback('show_archived', function (Builder $query, $value) {
                    if (!$value) {
                        $query->whereNull("tasks.archived_at");
                    }
                }),
                AllowedFilter::callback('show_completed', function (Builder $query, $value) {
                    if (!$value) {
                        $query->whereNull("tasks.completed_at");
                    }
                }),

            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'completed_at', 'due_date', 'name', 'prioritization', 'description')
            ->select('tasks.*')
            ->paginate(9);
    }
}
