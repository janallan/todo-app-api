<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1_0_0\TaskRequest;
use App\Http\Resources\V1_0_0\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * Create new Controller instance
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function index()
    {
        //getUserTasks
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\V1_0_0\TaskRequest  $request
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function store(TaskRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        $tasksCount = $user->tasks->count();

        $data['order_number'] = $tasksCount + 1;

        $task = $user->tasks()->create($data);

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function show(int $taskId)
    {
        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();

        return TaskResource::make($task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\V1_0_0\TaskRequest  $request
     * @param  int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function update(TaskRequest $request, int $taskId)
    {
        $user = $request->user();

        $data = $request->validated();

        $task = $user->tasks->where('id', $taskId)->first();

        $task->update($data);

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function destroy(int $taskId)
    {
        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();
        $task->softDeletes();

        return TaskResource::make($task);
    }

    /**
     * Mark the task as completed
     *
     * @param int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function completed(int $taskId)
    {

        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();
        $task->update([
            'status' => TaskStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        return TaskResource::make($task);
    }

    /**
     * Mark the task as todo
     *
     * @param int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function todo(int $taskId)
    {

        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();
        $task->update([
            'status' => TaskStatus::TODO,
            'completed_at' => null,
        ]);

        return TaskResource::make($task);
    }

    /**
     * Archive task
     *
     * @param int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function archived(int $taskId)
    {

        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();
        $task->update([
            'status' => TaskStatus::ARCHIVED,
            'archived_at' => now(),
        ]);

        return TaskResource::make($task);
    }


    /**
     * Restore the arhived task
     *
     * @param int $taskId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function restore(int $taskId)
    {

        $user = request()->user();
        $task = $user->tasks->where('id', $taskId)->first();
        $task->update([
            'status' => $task->completd_at ? TaskStatus::COMPLETED : TaskStatus::TODO,
            'archived_at' => null,
            'restored_at' => now(),
        ]);

        return TaskResource::make($task);
    }

}
