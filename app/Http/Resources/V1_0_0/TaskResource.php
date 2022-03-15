<?php

namespace App\Http\Resources\V1_0_0;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'days_before_due' => now()->startOfDay()->diffInDays($this->due_date),
            'prioritization' => $this->prioritization,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'completed_at' => $this->completed_at,
            'archived_at' => $this->archived_at,
            'restored_at' => $this->restored_at,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tags' => json_decode($this->tags),
            // 'attachments' => $this->attachments,
            'attachments' => MediaResource::collection($this->getMedia('task-attachments')),

        ];
    }
}
