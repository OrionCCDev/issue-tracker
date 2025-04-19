<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'meeting_date',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_attendees')
            ->withPivot('attendance_status')
            ->withTimestamps();
    }

    public function discussedIssues()
    {
        return $this->belongsToMany(Issue::class, 'meeting_issues')
            ->withPivot('status_before', 'status_after', 'notes')
            ->withTimestamps();
    }

    public function projectChanges()
    {
        return $this->hasMany(ProjectChange::class);
    }
}
