<?php

namespace Modules\ProductService\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'created_by',
        'workspace_id',
    ];

    protected static function newFactory()
    {
        return \Modules\ProductService\Database\factories\TaxFactory::new();
    }

    public static function tax($taxes)
    {

        $taxArr = explode(',', $taxes);
        $taxes  = [];
        foreach($taxArr as $tax)
        {
            $taxes[] = Tax::find($tax);
        }

        return $taxes;
    }
}
