<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceTax extends Model
{
    use HasFactory;

    protected $table = 'allowance_taxs';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\AllowanceTaxFactory::new();
    }
}
