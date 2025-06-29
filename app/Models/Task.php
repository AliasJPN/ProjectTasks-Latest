<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

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

    protected $casts = [
        'due_date_start' => 'datetime',
        'due_date_end' => 'datetime',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function getFormattedDate(string $column, string $format = 'Y年m月d日'): ?string
    {
        $date = $this->$column;

        return $date ? $date->format($format) : null;
    }
}
