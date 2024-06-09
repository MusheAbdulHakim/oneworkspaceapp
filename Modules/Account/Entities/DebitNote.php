<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebitNote extends Model
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
        return \Modules\Account\Database\factories\DebitNoteFactory::new();
    }
}
