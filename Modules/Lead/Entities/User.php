<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;


    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\UserFactory::new();
    }
    public function deals()
    {
        return $this->belongsToMany('Modules\Lead\Entities\Deal', 'user_deals', 'user_id', 'deal_id');
    }

    public function leads()
    {
        return $this->belongsToMany('Modules\Lead\Entities\Lead', 'user_leads', 'user_id', 'lead_id');
    }

    public function clientDeals()
    {
        return $this->belongsToMany('Modules\Lead\Entities\Deal', 'client_deals', 'client_id', 'deal_id');
    }

    public function clientEstimations()
    {
        return $this->hasMany('Modules\Lead\Entities\Estimation', 'client_id', 'id');
    }

    public function clientContracts()
    {
        return $this->hasMany('Modules\Lead\Entities\Contract', 'client_name', 'id');
    }

    public static function clientPermission($dealId)
    {
        return ClientPermission::where('client_id', '=', $this->id)->where('deal_id', '=', $dealId)->first();
    }

}
