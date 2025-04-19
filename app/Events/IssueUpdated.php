<?php
// app/Events/IssueUpdated.php
namespace App\Events;

use App\Models\Issue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class IssueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $issue;
    public $changedFields;

    /**
     * Create a new event instance.
     */
    public function __construct(Issue $issue, array $changedFields = [])
    {
        $this->issue = $issue;
        $this->changedFields = $changedFields;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->issue->project_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'issue.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $user = Auth::user();

        return [
            'id' => $this->issue->id,
            'title' => $this->issue->title,
            'description' => $this->issue->description,
            'priority' => $this->issue->priority,
            'status' => $this->issue->status,
            'target_resolution_date' => $this->issue->target_resolution_date,
            'actual_resolution_date' => $this->issue->actual_resolution_date,
            'assigned_to' => $this->issue->assignees->pluck('id')->toArray(),
            'notes' => $this->issue->notes,
            'updater' => [
                'id' => $user ? $user->id : null,
                'name' => $user ? $user->name : 'System',
            ],
            'updated_at' => now()->toIso8601String(),
            'changed_fields' => $this->changedFields,
        ];
    }
}
