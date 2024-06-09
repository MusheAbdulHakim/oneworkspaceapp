<?php

namespace Modules\Taskly\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id','file_name','file_path'
    ];

    protected static function newFactory()
    {
        return \Modules\Taskly\Database\factories\ProjectFileFactory::new();
    }
}
