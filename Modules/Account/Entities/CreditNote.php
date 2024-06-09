<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditNote extends Model
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
        return \Modules\Account\Database\factories\CreditNoteFactory::new();
    }
}
