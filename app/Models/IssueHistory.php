<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'updated_by',
        'title',
        'description',
        'priority',
        'status',
        'target_resolution_date',
        'actual_resolution_date',
        'notes',
        'changes',
    ];

    protected $casts = [
        'target_resolution_date' => 'date',
        'actual_resolution_date' => 'date',
        'changes' => 'array'
    ];

    /**
     * Get the issue that owns this history entry.
     */
    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who made this change.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
