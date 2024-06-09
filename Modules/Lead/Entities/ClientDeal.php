<?php

namespace Modules\Lead\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientDeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'deal_id'
    ];

    protected static function newFactory()
    {
        return \Modules\Lead\Database\factories\ClientDealFactory::new();
    }
}
