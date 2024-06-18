<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'url','title','description','color','note','type','image','status','user_id','order'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
