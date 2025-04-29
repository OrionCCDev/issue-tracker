<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image_path',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function assignedIssues()
    {
        return $this->belongsToMany(Issue::class, 'issue_assignees');
    }

    public function createdIssues()
    {
        return $this->hasMany(Issue::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user's notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(UserNotification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications
     */
    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function canCommentOnIssue(Project $project, Issue $issue)
    {
        // Admin users can always comment
        if ($this->role === 'o-admin') {
            return true;
        }

        // Project manager can always comment on their project's issues
        if ($this->id === $project->manager_id) {
            return true;
        }

        // CM users can comment on any issue
        if ($this->role === 'cm') {
            return true;
        }

        // Project members can comment on issues in their project
        if ($project->members->contains($this)) {
            return true;
        }

        // Issue assignees can comment on their assigned issues
        if ($issue->assignees->contains($this)) {
            return true;
        }

        // Issue creator can comment on their own issues
        if ($issue->created_by === $this->id) {
            return true;
        }

        return false;
    }
}
