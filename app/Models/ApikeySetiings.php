<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApikeySetiings extends Model
{
    use HasFactory;
    protected $table='api_key_settings';
    protected $fillable = [
    	'key',
    	'created_by'
    ];
}
