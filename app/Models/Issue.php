<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'project_id',
        'created_by',
        'target_resolution_date',
        'actual_resolution_date',
        'notes',
        'is_read',
    ];

    protected $casts = [
        'target_resolution_date' => 'date',
        'actual_resolution_date' => 'date',
        'is_read' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'issue_assignees')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the history entries for this issue.
     */
    public function history()
    {
        return $this->hasMany(IssueHistory::class)->orderBy('created_at', 'desc');
    }

    // Accessor for backward compatibility
    public function getAssignedToAttribute()
    {
        return $this->assignees()->first()->id ?? null;
    }

    // Get the first assignee for backward compatibility
    public function getAssignedToUserAttribute()
    {
        return $this->assignees()->first();
    }
}
