<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * Primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'task_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
        'description',
        'title',
        'module_id',
        'deadline',
        'module_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Get the module that owns the task.
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'module_id');
    }
}
