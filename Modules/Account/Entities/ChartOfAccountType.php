<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccountType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workspace',
        'created_by',
    ];


    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\ChartOfAccountTypeFactory::new();
    }
}
