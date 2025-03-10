<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nim',
        'password',
        'role',
        'nama'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'role',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Memeriksa apakah pengguna memiliki peran tertentu.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'mentor_id', 'id');
    }

    public function supervisedCourses()
    {
        return $this->hasMany(Course::class, 'pembimbing_id', 'id');
    }

    public function courseUsers()
    {
        return $this->hasMany(CourseUser::class);
    }

    public function attendanceUsers()
    {
        return $this->hasMany(AttendanceUser::class, 'user_id', 'id');
    }
}
