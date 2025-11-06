<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Mail\NewTask;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        if (app()->environment() !== 'production') {
            Log::info(self::class . ' send new mail');
            return;
        }

        $project->users()->each(function ($user) use ($creator, $task, $project) {
            if ($user->id === $creator->id) {
                return;
            }
            Mail::to($user->email)->queue(new NewTask($project->name, $task));
        });
    }
}
