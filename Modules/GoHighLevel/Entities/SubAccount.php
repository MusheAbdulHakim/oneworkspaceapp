<?php

namespace Modules\GoHighLevel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'gohighlevel_id','user_id','workspace',
        'snapshot','social','permissions','scopes',
        'ghl_user_id','locationId'
    ];

    protected $casts = [
        'permissions' => 'collection',
        'scopes' => 'collection',
    ];

    public function gohighlevel(){
        return $this->belongsTo(Gohighlevel::class,'gohighlevel_id');
    }

    public function token(){
        return $this->hasOne(SubaccountToken::class,'sub_account_id');
    }

}
