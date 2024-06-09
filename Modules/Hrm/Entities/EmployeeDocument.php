<?php

namespace Modules\Hrm\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id','document_id','document_value','workspace','created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Hrm\Database\factories\EmployeeDocumentFactory::new();
    }
}
