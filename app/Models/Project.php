<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id',);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


}