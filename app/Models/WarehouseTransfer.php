<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_warehouse',
        'to_warehouse',
        'product_id',
        'quantity',
        'date',
        'created_by',
    ];

    public function product()
    {
        if(module_is_active('ProductService'))
        {
            return $this->hasOne(\Modules\ProductService\Entities\ProductService::class, 'id', 'product_id');   //->first()
        }
    }


    public function fromWarehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'from_warehouse'); //->first()
    }
    public function toWarehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'to_warehouse');   //->first()
    }

      //warehouse transfer
      public static function warehouse_transfer_qty($from_warehouse,$to_warehouse,$product_id,$quantity,$delete=null)
      {

          $toWarehouse      = WarehouseProduct::where('warehouse_id',$to_warehouse)->where('product_id',$product_id)->first();
          if(empty($toWarehouse)){
              if($delete != 'delete')
              {
                  $transfer                = new WarehouseProduct();
                  $transfer->warehouse_id  = $to_warehouse;
                  $transfer->product_id    = $product_id;
                  $transfer->quantity      = $quantity;
                  $transfer->workspace     = getActiveWorkSpace();
                  $transfer->created_by    = creatorId();
                  $transfer->save();
              }
          }else{
              $toWarehouse->quantity   = $toWarehouse->quantity+$quantity;
              $toWarehouse->save();
          }
          $fromWarehouse               = WarehouseProduct::where('warehouse_id',$from_warehouse)->where('product_id',$product_id)->first();
          if(!empty($fromWarehouse))
          {
              $fromWarehouse->quantity     = ($fromWarehouse->quantity) - ($quantity);
              if($fromWarehouse->quantity <= 0){
                  $fromWarehouse->delete();
              }
              else{
                  $fromWarehouse->save();
              }
          }


      }
}
