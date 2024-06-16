<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinnedAppCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','user_id'
    ];

    public function apps(){
        return $this->hasMany(PinnedApp::class,'pinned_app_category_id');
    }
}
