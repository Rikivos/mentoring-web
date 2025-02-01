<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $primaryKey = 'module_id';
    protected $fillable = ['module_title', 'content', 'course_id', 'file_path'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'module_id', 'module_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'module_id', 'module_id');
    }
}
