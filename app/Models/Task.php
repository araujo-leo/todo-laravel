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
        'total_pomodoro',
        'pomodoro_value',
        'completed_pomodoro',
        'status',
        'task_date',
        'due_date',
        'assigned_at',
        'completed_at',
        'user_id',
    ];
}
