<?php

namespace App\Http\Middleware;

use App\Enums\TaskStatus;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AutoDeleteArchivedTaskMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @todo Create Logging
     */
    public function handle(Request $request, Closure $next)
    {

        $date = now()->subWeek();

        $tasks = Task::where('status', TaskStatus::ARCHIVED)->whereRaw("DATE(archived_at) <= DATE('{$date}')")->get();

        foreach ($tasks as $task) {
            $task->delete();
        }

        return $next($request);
    }
}
