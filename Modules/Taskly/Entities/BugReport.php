<?php

namespace Modules\Taskly\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BugReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'assign_to',
        'project_id',
        'status',
        'order',
    ];
    public static $arrStatus   = [
        'unconfirmed',
        'confirmed',
        'in progress',
        'resolved',
        'verified',
    ];

    protected static function newFactory()
    {
        return \Modules\Taskly\Database\factories\BugReportFactory::new();
    }
    public function project()
    {
        return $this->hasOne('Modules\Taskly\Entities\Project', 'id', 'project_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'assign_to');
    }

    public function stage()
    {
        return $this->hasOne('Modules\Taskly\Entities\BugStage', 'id', 'status');
    }

    public function comments()
    {
        return $this->hasMany('Modules\Taskly\Entities\BugComment', 'bug_id', 'id')->orderBy('id', 'DESC');
    }

    public function bugFiles()
    {
        return $this->hasMany('Modules\Taskly\Entities\BugFile', 'bug_id', 'id')->orderBy('id', 'DESC');
    }
}
