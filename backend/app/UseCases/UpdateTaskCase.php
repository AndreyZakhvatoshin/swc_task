<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Dto\UpdateTaskDto;
use App\Models\Task;

class UpdateTaskCase
{
    public function __invoke(Task $task, UpdateTaskDto $dto)
    {
        $task->update($dto->toArray());

        $attachments = $task->getMedia('attachments');

        // если не переданы ссылки на файлы - удаляем
        if (is_null($dto->attachments_url) || count($dto->attachments_url) < $attachments->count()) {
            $attachments->filter(function ($attachment) use ($dto) {
                return !in_array($attachment->getUrl(), $dto->attachments_url);
            })->each(fn ($attachment) => $attachment->delete());
        }

        if ($dto->attachments) {
            foreach ($dto->attachments as $attachment) {
                $task->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $task;
    }
}
