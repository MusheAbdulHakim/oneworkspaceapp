<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id','name','date','time','priority','status','workspace'
    ];

    public static $priorities = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
    ];
    public static $status = [
        0 => 'On Going',
        1 => 'Completed'
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealTaskFactory::new();
    }
}
