<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\CreateInvoice;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;
use Modules\ProductService\Entities\ProductService;
use Modules\ProductService\Entities\Tax;
use Modules\Taskly\Entities\Project;

class CreateInvoiceLis
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
    public function handle(CreateInvoice $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $invoice = $event->invoice;
            $items = $request['items'];
            
            $category_data = null;
            if ($invoice->invoice_module == "account") {
                if (array_column($request->items, 'item') && $request->invoice_type == 'product') {
                    $product =  array_column($request->items, 'item');
                    $product = ProductService::whereIn('id', $product)->get()->pluck('name')->toArray();
                    if (count($product) > 0) {
                        $product_name = implode(',', $product);
                    }
                    $invoice->product = $product_name;
                }

                if ($invoice->category_id) {
                    $category = Category::where('id', $invoice->category_id)->where('type', 1)->first();
                    $invoice->category_name = $category->name;
                    $category_data = $category->name;
                } else {
                    $project = Project::find($request->project);
                    $category_data = $project->name;
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
                unset($invoice->user_id);
            }

            $action = 'New Invoice';
            $module = 'general';

            $pabbly_array = array(
                "customer_id"    => $invoice['customer_id'],
                "invoice_id"     => $invoice['invoice_id'],
                "invoice_module" => $invoice['invoice_module'],
                "issue_date"     => $invoice['issue_date'],
                "due_date"       => $invoice['due_date'],
                "category"       => $category_data,
                "Items"          => $items,
                "Total"          => $invoice->getTotal(),
            );
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}