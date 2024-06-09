<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'warning_to',
        'warning_by',
        'subject',
        'warning_date',
        'description',
        'workspace',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\WarningFactory::new();
    }
}
