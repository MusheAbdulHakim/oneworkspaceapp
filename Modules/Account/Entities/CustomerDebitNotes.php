<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerDebitNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill',
        'vendor',
        'amount',
        'date',
    ];

    protected static function newFactory()
    {
        return \Modules\Account\Database\factories\CustomerDebitNotesFactory::new();
    }

    public function vendor()
    {
        return $this->hasOne(\Modules\Account\Entities\Vender::class, 'vender_id', 'vendor');
    }

    public static $statues = [
        'Pending',
        'Partially Used',
        'Fully Used',
    ];
}
