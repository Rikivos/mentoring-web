<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_name',
        'report_photo',
        'description',
        'start_time',
        'end_time',
        'upload_date',
        'course_id',
        'user_id',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending', // Default value
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
