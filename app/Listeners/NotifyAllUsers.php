<?php

namespace App\Listeners;

use App\Events\ProjectCreated;
use App\Models\User;
use App\Notifications\ProjectCreated as ProjectCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAllUsers implements ShouldQueue
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
    public function handle(ProjectCreated $event): void
    {
        // Get all users
        $users = User::all();

        // Notify all users about the new project
        foreach ($users as $user) {
            $user->notify(new ProjectCreatedNotification($event->project));
        }
    }
}
