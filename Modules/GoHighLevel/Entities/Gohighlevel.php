<?php

namespace Modules\GoHighLevel\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gohighlevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token','access_expires_in','token_type','userType','companyId','locationId','userId','user_id','workspace'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


}
