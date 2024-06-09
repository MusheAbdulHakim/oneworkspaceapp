<?php

namespace Modules\ProductService\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Account\Entities\ChartOfAccount;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [];
    public static $categoryType = [
        'Product & Service',
        'Income',
        'Expense',
    ];
    protected static function newFactory()
    {
        return \Modules\ProductService\Database\factories\CategoryFactory::new();
    }


    public function chartAccount()
    {
        return $this->hasOne(ChartOfAccount::class, 'id', 'chart_account_id');
    }

}

