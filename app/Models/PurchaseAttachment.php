<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseAttachment extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'file_name',
        'file_path',
        'file_size',
    ];
    
}
