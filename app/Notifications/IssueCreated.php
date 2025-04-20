<?php

namespace App\Notifications;

use App\Models\Issue;
use App\Models\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $issue;

    /**
     * Create a new notification instance.
     */
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
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
            ->subject('New Issue: ' . $this->issue->title)
            ->line('A new issue has been created for a project you are involved with.')
            ->line('Project: ' . $this->issue->project->name)
            ->line('Issue: ' . $this->issue->title)
            ->line('Priority: ' . $this->issue->priority)
            ->line('Status: ' . $this->issue->status)
            ->line('Created by: ' . $this->issue->creator->name)
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
            'created_by_id' => $this->issue->created_by,
            'created_by_name' => $this->issue->creator->name,
            'type' => 'issue_created',
            'message' => 'New issue "' . $this->issue->title . '" has been created by ' . $this->issue->creator->name,
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
            'type' => 'App\\Notifications\\IssueCreated',
            'notifiable_id' => $this->issue->id,
            'notifiable_type' => get_class($this->issue),
            'data' => $this->toArray($notifiable),
        ]);

        return $this->toArray($notifiable);
    }
}
