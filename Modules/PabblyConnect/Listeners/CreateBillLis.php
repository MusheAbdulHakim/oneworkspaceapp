<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\Vender;
use Modules\Account\Events\CreateBill;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;
use Modules\ProductService\Entities\ProductService;
use Modules\ProductService\Entities\Tax;
use Modules\Taskly\Entities\Project;

class CreateBillLis
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
    public function handle(CreateBill $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $bill = $event->bill;
            $items = $request['items'];
            $category_data = null;

            if (array_column($request->items, 'item') && $request->bill_type == "product") {
                $product =  array_column($request->items, 'item');
                $product = ProductService::get()->pluck('name')->toArray();
                if (count($product) > 0) {
                    $product_name = implode(',', $product);
                }
                if ($bill->category_id) {
                    $category = Category::where('id', $bill->category_id)->where('type', 2)->first();
                    $category_data = $category->name;
                }
                $bill->product = $product_name;
            } else {
                $project = Project::find($request->project);
                $category_data = $project->name;
            }
            if ($bill->vendor_id) {
                $vendor = Vender::find($bill->vendor_id);
            }

            if(!empty($items)){
                foreach ($items as $key =>  $item) {
                    $taxForItem = $item['tax'];
                    $x =[];
                    foreach (explode(',',$taxForItem) as $key => $item1) {

                        $tax = Tax::where('id',$item1)->first();
                        if ($tax) {
                            $x[$key] = $tax->name;
                        }

                    }
                    $item['tax_name'] = implode(',' , $x);
                    $items[$key]['TaxName'] = $item['tax_name'];

                    $items = array_map(function ($item) {
                        // Remove the "id" key from each array
                        unset($item['id']);
                        return $item;
                    }, $items);
                }
            } 

            unset($bill->user_id);
            $action = 'New Bill';
            $module = 'Account';
            $pabbly_array = array(
                "bill_id"     => $bill['bill_id'],
                "user"        => $vendor->name,
                "bill_date"   => $bill['bill_date'],
                "due_date"    => $bill['due_date'],
                "category"    => $category_data,
                "Item"        => $items,
                "Total"       => $bill->getTotal(),
                "workspace"   => $bill['workspace'],
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}