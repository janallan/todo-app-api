<?php

namespace App\Http\Controllers\Api\V1_0_0;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1_0_0\TaskRequest;
use App\Http\Resources\V1_0_0\TagResource;
use App\Http\Resources\V1_0_0\TaskResource;
use App\Models\Media;
use App\Models\Tag;
use App\Models\Task;
use App\Repositories\Task\Contracts\TaskInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{

    /**
     * Create new Controller instance
     */
    public function __construct(
        private TaskInterface $taskInterface
    )
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
        $user = request()->user();

        $tasks = $this->taskInterface->getUserTasks($user->id);

        return TaskResource::collection($tasks);
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
        $data['status'] = TaskStatus::TODO;
        $data['tags'] = json_encode(array_key_exists('tags', $data) ? $data['tags'] : []);

        $task = $user->tasks()->create($data);

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function show(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to view this task');
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
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function update(TaskRequest $request, Task $task)
    {
        abort_if($request->user()->cannot('update', $task), 401, 'You are not authorized to update this task');

        $data = $request->validated();

        $task->update($data);

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function destroy(Task $task)
    {
        abort_if(request()->user()->cannot('delete', $task), 401, 'You are not authorized to delete this task');
        $task->delete();

        return response('',204);
    }

    /**
     * Mark the task as completed
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function completed(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to incomplete this task');

        $task->update([
            'status' => TaskStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        return TaskResource::make($task);
    }

    /**
     * Mark the task as incomeplete
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function incomeplete(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to update this task');

        $task->update([
            'status' => TaskStatus::TODO,
            'completed_at' => null,
        ]);

        return TaskResource::make($task);
    }

    /**
     * Archive task
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function archived(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to archive this task');

        $task->update([
            'status' => TaskStatus::ARCHIVED,
            'archived_at' => now(),
        ]);

        return TaskResource::make($task);
    }


    /**
     * Restore the arhived task
     *
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function restore(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to restore this task');

        $task->update([
            'status' => $task->completd_at ? TaskStatus::COMPLETED : TaskStatus::TODO,
            'archived_at' => null,
            'restored_at' => now(),
        ]);

        return TaskResource::make($task);
    }

    /**
     * Get Available Tags for the Task
     *
     * @param \App\Models\Task
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function availableTags(Task $task)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to remove attached files to this task');

        $user = request()->user();

        $available = $user->tags;

        return TagResource::collection($available);

    }

    /**
     * Set Tags
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function setTags(Task $task, Request $request)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to update this task');

        $data = $request->validate([
            'tags' => ['nullable', 'array']
        ]);

        $user = $request->user();

        foreach ($data['tags'] as $key => $tag) {
            $tag = strtoupper($tag);

            $exist = $user->tags->where('name', $tag)->first();
            if (!$exist) {
                $user->tags()->create(['name' => $tag]);
            }

            $data['tags'][$key] = $tag;
        }

        $data['tags'] = array_unique($data['tags']);

        $task->update($data);
        $task->refresh();

        return TaskResource::make($task);


    }

    /**
     * Add Tag
     *
     * @param \App\Models\Task $task
     * @param string $tagName
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function addTag(Task $task, $tagName)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to update this task');

        $tag = Tag::where('name', $tagName)->firstOrFail();
        abort_if(request()->user()->cannot('update', $tag), 401, 'You are not authorized to use this tag');

        $tags = $task->tags === '' ? [] : json_decode($task->tags);

        if (in_array($tagName, $tags)) {
            abort(422, 'Tag already exist in the task');
        }

        array_push($tags, $tagName);
        $task->tags = json_encode($tags);
        $task->save();
        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Remove Tag
     *
     * @param \App\Models\Task $task
     * @param string $tagName
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function removeTag(Task $task, $tagName)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to update this task');

        $tag = Tag::where('name', $tagName)->firstOrFail();
        abort_if(request()->user()->cannot('update', $tag), 401, 'You are not authorized to use this tag');

        $tags = $task->tags === '' ? [] : json_decode($task->tags);


        if (($key = array_search($tagName, $tags)) !== false) {
            unset($tags[$key]);
            $task->tags = json_encode(array_values($tags));
            $task->save();
        }
        else {
            abort(422, 'Tag doesn\'t exist in the task');
        }

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Attach File to Task
     *
     * @param \App\Models\Task $task
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function uploadAttachments(Task $task, Request $request)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to attach files to this task');
        Log::info($request->all());

        $request->validate([
            'attachments.*' => ['required', 'file', 'max:2000', 'mimes:svg,png,jpg,mp4,csv,txt,doc,docx'],
        ]);

        foreach ($request->file('attachments') as $file) {
            $task->addMedia($file)->toMediaCollection('task-attachments');
        }

        $task->refresh();

        return TaskResource::make($task->load('attachments'));
    }

    /**
     * Delete Attachment from Task
     *
     * @param \App\Models\Task $task
     * @param \App\Models\Media $media
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function deleteAttachment(Task $task, Media $media)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to remove attached files to this task');

        abort_if(!$task->attachments?->where('id', $media->id)->first(), 401, 'Attachment not found in the task');

        $task->deleteMedia($media->id);

        $task->refresh();

        return TaskResource::make($task);
    }

    /**
     * Download Attachment from Task
     *
     * @param \App\Models\Task $task
     * @param \App\Models\Media $media
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function downloadAttachment(Task $task, Media $media)
    {
        abort_if(request()->user()->cannot('update', $task), 401, 'You are not authorized to remove attached files to this task');

        abort_if(!$task->attachments?->where('id', $media->id)->first(), 401, 'Attachment not found in the task');


        return URL::temporarySignedRoute(
            'download.task-attachment', now()->addMinutes(5), ['media' => $media->id]
        );
    }

}
