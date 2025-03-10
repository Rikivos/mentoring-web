<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Course extends Model
{
    use HasFactory, Sluggable;

    protected $table = 'courses';

    protected $primaryKey = 'course_id';

    public $timestamps = true;

    protected $fillable = [
        'course_title',
        'course_slug',
        'mentor_id',
        'pembimbing_id',
    ];

    public function sluggable(): array
    {
        return [
            'course_slug' => [
                'source' => 'course_title'
            ]
        ];
    }


    //relation
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'course_id');
    }

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id', 'course_id');
    }
}
