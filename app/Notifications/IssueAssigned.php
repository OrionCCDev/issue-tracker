<?php

namespace App\Notifications;

use App\Models\Issue;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $issue;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Issue $issue, User $assignedBy)
    {
        $this->issue = $issue;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Issue Assigned: ' . $this->issue->title)
            ->line('You have been assigned to an issue.')
            ->line('Project: ' . $this->issue->project->name)
            ->line('Issue: ' . $this->issue->title)
            ->line('Priority: ' . $this->issue->priority)
            ->line('Status: ' . $this->issue->status)
            ->line('Assigned by: ' . $this->assignedBy->name)
            ->action('View Issue', url('/projects/' . $this->issue->project_id . '/issues/' . $this->issue->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'issue_id' => $this->issue->id,
            'issue_title' => $this->issue->title,
            'project_id' => $this->issue->project_id,
            'project_name' => $this->issue->project->name,
            'assigned_by_id' => $this->assignedBy->id,
            'assigned_by_name' => $this->assignedBy->name,
            'type' => 'issue_assigned',
            'message' => 'You have been assigned to issue "' . $this->issue->title . '" by ' . $this->assignedBy->name,
        ];
    }

    /**
     * Store the notification in the database
     */
    public function toDatabase(object $notifiable): array
    {
        // Create a user notification
        UserNotification::create([
            'user_id' => $notifiable->id,
            'type' => 'App\\Notifications\\IssueAssigned',
            'notifiable_id' => $this->issue->id,
            'notifiable_type' => get_class($this->issue),
            'data' => $this->toArray($notifiable),
        ]);

        return $this->toArray($notifiable);
    }
}
