<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerCreditNotes extends Model
{
    use HasFactory;


    protected $fillable = [
        'invoice',
        'customer',
        'amount',
        'date',
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\CustomerCreditNotesFactory::new();
    }

    public function customer()
    {
        return $this->hasOne(\Modules\Account\Entities\Customer::class, 'customer_id', 'customer');
    }

    public static $statues = [
        'Pending',
        'Partially Used',
        'Fully Used',
    ];
}

