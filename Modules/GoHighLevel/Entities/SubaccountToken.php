<?php

namespace Modules\GoHighLevel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubaccountToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_account_id','access_token','token_type','expires_in',
        'refresh_token','scope','userType','companyId',
        'locationId','userId','traceId','user_id','workspace'
    ];


    public function subAccount(){
        return $this->belongsTo(SubAccount::class, 'sub_account_id');
    }
}
