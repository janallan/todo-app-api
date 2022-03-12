<?php

namespace App\Actions\V1_0_0\Tag;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class UpdateTag
{
    /**
     * Update Tag & Related Tasks
     *
     * @param \App\Models\Tag $tag
     * @param array $data
     * @return \App\Models\Tag
     * @creator Jan Allan Verano
     */
    public function __invoke(Tag $tag, array $data): Tag
    {

        $tag = DB::transaction(function () use($tag, $data){

            $oldName = $tag->name;
            $tag->update($data);
            $tag->refresh();

            $this->updateTasks($oldName, $tag->name);

            return $tag;
        });

        return $tag;
    }

    /**
     * Update Tasks with new Tag Name
     *
     * @param string $oldName
     * @param string $newName
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    private function updateTasks(string $oldName, string $newName)
    {
        $old = '"' . $oldName . '"';
        $new = '"' . $newName . '"';
        $user = request()->user();

        $tasks = Task::where('user_id', $user->id)->where('tags', 'like', '%' . $old . '%')->get();
        foreach ($tasks as $task) {
            $task->update(['tags' => str_replace($old, $new, $task->tags) ]);
        }

    }
}
