<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'status',
    ];

    public function attendanceUsers()
    {
        return $this->hasMany(AttendanceUser::class, 'attendance_id', 'attendance_id');
    }
}
