<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'title',
        'description',
        'totalPomodori',
        'pomodoroValue',
        'completedPomodori',
        'status',
        'taskDate',
        'dueDate',
        'assigned_at',
        'completed_at',
        'user_id',
    ];
}
