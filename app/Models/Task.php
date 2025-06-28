<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'task_name',
        'assigned_project_member_id',
        'status',
        'priority',
        'due_date_start',
        'due_date_end',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}