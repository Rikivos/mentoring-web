<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'attendances';

    // Primary key
    protected $primaryKey = 'attendance_id';

    // Kolom yang dapat diisi
    protected $fillable = [
        'module_id',
        'attendance_open',
        'deadline',
    ];

    /**
     * Relasi ke model Module.
     * Attendance belongs to a Module.
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }

    public function attendanceUsers()
    {
        return $this->hasMany(AttendanceUser::class, 'attendance_id', 'attendance_id');
    }
}
