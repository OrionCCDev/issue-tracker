<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Models\User;
use App\Models\Project;
use App\Models\Issue;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show the user's notifications
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = UserNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read');
    }

    /**
     * Mark all notifications as read for the current user
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read');
    }

    /**
     * Mark a notification as unread
     */
    public function markAsUnread($id)
    {
        $notification = UserNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsUnread();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as unread');
    }

    /**
     * Create sample notifications for testing
     */
    public function createSampleNotifications()
    {
        $user = Auth::user();

        // Create a test notification
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'test_notification',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => [
                'title' => 'Test Notification',
                'message' => 'This is a test notification to verify the system works.',
                'url' => route('dashboard')
            ]
        ]);

        // Try to get a project to associate with a notification
        $project = Project::first();
        if ($project) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'project_update',
                'notifiable_type' => Project::class,
                'notifiable_id' => $project->id,
                'data' => [
                    'title' => 'Project Update',
                    'message' => 'Project "' . $project->name . '" has been updated.',
                    'url' => route('projects.show', ['project' => $project->id])
                ]
            ]);
        }

        // Try to get an issue to associate with a notification
        $issue = Issue::first();
        if ($issue) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'issue_assigned',
                'notifiable_type' => Issue::class,
                'notifiable_id' => $issue->id,
                'data' => [
                    'title' => 'Issue Assigned',
                    'message' => 'You have been assigned to issue "' . $issue->title . '".',
                    'url' => route('issues.show', $issue->id)
                ]
            ]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Sample notifications created successfully.');
    }

    /**
     * Get unread notifications count and latest notifications
     * Endpoint for AJAX polling
     */
    public function getNotificationsData()
    {
        $user = Auth::user();
        $unreadCount = $user->unreadNotifications()->count();
        $latestNotifications = $user->notifications()->latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'unreadCount' => $unreadCount,
            'latestNotifications' => $latestNotifications,
            'hasUnread' => $unreadCount > 0
        ]);
    }
}
