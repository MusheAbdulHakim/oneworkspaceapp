<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DealFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id','file_name','file_path'
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\DealFileFactory::new();
    }
}
