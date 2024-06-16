<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','title','startDate','endDate',
        'url','color','description','data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
