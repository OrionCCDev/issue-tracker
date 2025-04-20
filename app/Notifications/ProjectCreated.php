<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\UserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $project;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
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
            ->subject('New Project: ' . $this->project->name)
            ->line('A new project has been created.')
            ->line('Project: ' . $this->project->name)
            ->line('Code: ' . $this->project->code)
            ->line('Manager: ' . ($this->project->manager ? $this->project->manager->name : 'Not assigned'))
            ->action('View Project', url('/projects/' . $this->project->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $managerName = $this->project->manager ? $this->project->manager->name : 'Not assigned';

        return [
            'project_id' => $this->project->id,
            'project_name' => $this->project->name,
            'manager_id' => $this->project->manager_id,
            'manager_name' => $managerName,
            'type' => 'project_created',
            'message' => 'New project "' . $this->project->name . '" has been created',
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
            'type' => 'App\\Notifications\\ProjectCreated',
            'notifiable_id' => $this->project->id,
            'notifiable_type' => get_class($this->project),
            'data' => $this->toArray($notifiable),
        ]);

        return $this->toArray($notifiable);
    }
}
