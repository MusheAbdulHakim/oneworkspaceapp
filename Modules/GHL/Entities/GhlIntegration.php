<?php

namespace Modules\GHL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GhlIntegration extends Model
{
    use HasFactory;
    
    protected $table = "ghl_integrations";

    protected $fillable = [
        'access_token','access_expires_in','token_type','userType','companyId','locationId','userId','user_id','workspace'    
    ];
    
    
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    
    protected static function newFactory()
    {
        // return \Modules\GHL\Database\factories\GhlIntegrationFactory::new();
    }
}
