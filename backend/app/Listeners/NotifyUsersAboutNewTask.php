<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Mail\NewTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersAboutNewTask
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        $creator = $event->user;
        $task = $event->task;
        $project = $event->project;
        $project->users()->each(function ($user) use ($creator, $task, $project) {
            if ($user->id === $creator->id) {
                return;
            }
            $user->notify(new NewTask($project->name, $task));
        });
    }
}
