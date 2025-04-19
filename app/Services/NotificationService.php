<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    /**
     * Create a notification for a specific user
     */
    public static function notify(User $user, string $type, Model $notifiable, array $data = []): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'data' => $data,
        ]);
    }

    /**
     * Create a notification for multiple users
     */
    public static function notifyMany($users, string $type, Model $notifiable, array $data = []): void
    {
        // Convert Collection to array if needed
        $usersArray = $users instanceof \Illuminate\Database\Eloquent\Collection ? $users->all() : $users;

        foreach ($usersArray as $user) {
            self::notify($user, $type, $notifiable, $data);
        }
    }

    /**
     * Create a notification for all users except the specified one
     */
    public static function notifyOthers(?User $excludeUser, string $type, Model $notifiable, array $data = []): void
    {
        $users = User::when($excludeUser, function ($query) use ($excludeUser) {
            return $query->where('id', '!=', $excludeUser->id);
        })->get();

        self::notifyMany($users, $type, $notifiable, $data);
    }

    /**
     * Create notifications for users associated with a project
     */
    public static function notifyProjectMembers(int $projectId, string $type, Model $notifiable, array $data = [], ?User $excludeUser = null): void
    {
        $users = User::whereHas('projects', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->when($excludeUser, function ($query) use ($excludeUser) {
            return $query->where('id', '!=', $excludeUser->id);
        })->get();

        self::notifyMany($users, $type, $notifiable, $data);
    }
}
