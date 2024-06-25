<?php

namespace Modules\GoHighLevel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'gohighlevel_id','user_id','workspace',
        'snapshot','social','permissions','scopes','ghl_user_id','locationId'
    ];

    public function gohighlevel(){
        return $this->belongsTo(Gohighlevel::class,'gohighlevel_id');
    }

}
