<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'project_id',
        'field_name',
        'old_value',
        'new_value',
        'changed_by',
        'notes'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
