<?php

namespace App\Listeners;

use App\Events\IssueCreated;
use App\Models\User;
use App\Notifications\IssueCreated as IssueCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NotifyProjectMembers implements ShouldQueue
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
    public function handle(IssueCreated $event): void
    {
        $issue = $event->issue;
        $project = $issue->project;

        // Get users to notify based on their roles
        $usersToNotify = $this->getUsersToNotify($project, $issue);

        // Remove duplicates (a user might be both admin and project manager)
        $uniqueUsers = $usersToNotify->unique('id');

        // Notify each user
        foreach ($uniqueUsers as $user) {
            $user->notify(new IssueCreatedNotification($issue));
        }
    }

    /**
     * Get users to notify based on their roles
     */
    private function getUsersToNotify($project, $issue): \Illuminate\Support\Collection
    {
        $usersToNotify = collect();

        // 1. Get all admin users
        $adminUsers = User::where('role', 'admin')->get();
        $usersToNotify = $usersToNotify->merge($adminUsers);

        // 2. Get project manager
        if ($project->manager) {
            $usersToNotify->push($project->manager);
        }

        // 3. Get all GM (General Managers) users
        $gmUsers = User::where('role', 'gm')->get();
        $usersToNotify = $usersToNotify->merge($gmUsers);

        // 4. Get all CM (Constructions Managers) users
        $cmUsers = User::where('role', 'cm')->get();
        $usersToNotify = $usersToNotify->merge($cmUsers);

        // 5. Get all users assigned to this issue
        if ($issue->assignees) {
            $usersToNotify = $usersToNotify->merge($issue->assignees);
        }

        return $usersToNotify;
    }
}
