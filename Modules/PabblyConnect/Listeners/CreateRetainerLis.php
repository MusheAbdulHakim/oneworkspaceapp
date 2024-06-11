<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;
use Modules\ProductService\Entities\ProductService;
use Modules\Retainer\Events\CreateRetainer;

class CreateRetainerLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateRetainer $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $retainer = $event->retainer;
            if($retainer->retainer_module =="account")
            {
                if( array_column($request->items, 'item') && $request->retainer_type == 'product'){
                    $product=  array_column($request->items, 'item');
                    $product = ProductService::whereIn('id',$product)->get()->pluck('name')->toArray();
                    if(count($product) > 0)
                    {
                        $product_name = implode(',',$product);
                    }
                    $retainer->product = $product_name;
                }
                if($retainer->user_id){
                    $customer = User::find($retainer->user_id);
                    $retainer->customer_name = $customer->name;
                }
                if($request->category_id){
                    $category = Category::where('id',$request->category_id)->where('type', 1)->first();
                    $retainer->category_name = $category->name;
                }
            }
            unset($retainer->user_id);
            $action = 'New Retainer';
            $module = 'Retainer';

            $pabbly_array = array(
                "retainer_module" => $retainer['retainer_module'],
                "issue_date"      => $retainer['issue_date'],
                "Product"         => $product_name,
                "category"        => $category->name,
                "Retainer Items"  => $request->items,
            );
            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}