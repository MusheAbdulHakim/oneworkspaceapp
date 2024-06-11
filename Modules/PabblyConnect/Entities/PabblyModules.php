<?php

namespace Modules\PabblyConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PabblyModules extends Model
{
    use HasFactory;

    protected $fillable = [
        'module',
        'submodule',
    ];
    
    protected static function newFactory()
    {
        return \Modules\PabblyConnect\Database\factories\PabblyModulesFactory::new();
    }
}
