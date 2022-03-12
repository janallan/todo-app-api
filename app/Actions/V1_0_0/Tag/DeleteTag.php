<?php

namespace App\Actions\V1_0_0\Tag;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class DeleteTag
{
    /**
     * Delete Tag
     *
     * @param \App\Models\Tag $tag
     * @return void
     * @creator Jan Allan Verano
     */
    public function __invoke(Tag $tag): void
    {
        DB::transaction(function () use($tag){
            $tagName = $tag->name;
            $tag->delete();

            $this->updateTasks($tagName);
        });

    }

    /**
     * Remove Tag in Tasks
     *
     * @param string $tagName
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    private function updateTasks(string $tagName)
    {
        $tag = '"' . $tagName . '"';
        $user = request()->user();

        $tasks = Task::where('user_id', $user->id)->where('tags', 'like', '%' . $tag . '%')->get();
        foreach ($tasks as $task) {
            $tags = $task->tags === '' ? [] : json_decode($task->tags);

            if (($key = array_search($tagName, $tags)) !== false) {
                unset($tags[$key]);
                $task->tags = json_encode(array_values($tags));
                $task->save();
            }
        }

    }
}
