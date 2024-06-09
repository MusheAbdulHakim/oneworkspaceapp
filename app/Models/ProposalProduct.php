<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'product_id',
        'proposal_id',
        'quantity',
        'tax',
        'discount',
        'price',
        'description',
    ];

    public function product()
    {
        $proposal =  $this->hasMany(Proposal::class, 'id', 'proposal_id')->first();

        if(!empty($proposal) && $proposal->proposal_module == "account")
        {
            if(module_is_active('ProductService'))
            {
                return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id')->first();

            }
            else
            {
                return [];
            }
        }
        elseif(!empty($proposal) && $proposal->proposal_module == "taskly")
        {
            if(module_is_active('Taskly'))
            {
                return  $this->hasOne(\Modules\Taskly\Entities\Task::class, 'id', 'product_id')->first();
            }
            else
            {
                return [];
            }
        }
        elseif(!empty($proposal) && $proposal->proposal_module == "cmms")
        {
            if(module_is_active('ProductService'))
            {
                return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id')->first();
            }
            else
            {
                return [];
            }
        }

    }
}


