<?php

namespace Modules\PabblyConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pabbly extends Model
{
    use HasFactory;

    protected $table = 'pabbly_connections';

    protected $fillable = [
        'module',
        'method',
        'action',
        'url',
        'workspace_id',
        'created_by'
    ];

    
    protected static function newFactory()
    {
        return \Modules\PabblyConnect\Database\factories\PabblyFactory::new();
    }

    public function module(){
        return $this->hasOne(PabblyModules::class,'id','action');
    }


    public static $rates;
    public static $data;
    public static function getTaxData()
    {
        $data = [];
        if(self::$rates == null)
        {
            $rates          =  \Modules\ProductService\Entities\Tax::where('workspace_id',getActiveWorkSpace())->get();
            self::$rates    =  $rates;
            foreach(self::$rates as $rate)
            {
                $data[$rate->id]['name']        = $rate->name;
                $data[$rate->id]['rate']        = $rate->rate;
                $data[$rate->id]['created_by']  = $rate->created_by;
            }
            self::$data    =  $data;
        }
        return self::$data;
    }
}