<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->user;
        $mediaItems = $this->getMedia('attachments')
            ->map(function ($item) {
            return $item->getUrl();
        });

        return [
            'id' => $this->id,
            'user' => $user->name,
            'title' => $this->title,
            'status' => $this->status,
            'description' => $this->description,
            'completion_date' => $this->completion_date,
            'created_at' => $this->created_at,
            'attachments' => $mediaItems
        ];
    }
}
