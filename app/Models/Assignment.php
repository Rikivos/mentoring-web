<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'assignments';

    // Primary key
    protected $primaryKey = 'assignment_id';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'file',
        'assignment_date',
        'task_id',
        'user_id',
    ];

    // Relasi dengan model Task
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id', 'task_id');
    }

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
