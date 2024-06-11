<?php

namespace Modules\PabblyConnect\Listeners;

use App\Events\CreateProposal;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateProposalLis
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
    public function handle(CreateProposal $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $proposal = $event->proposal;
            if($proposal->proposal_module == "account"){
                if( array_column($request->items, 'item')){
                    $product=  array_column($request->items, 'item');
                    $product = \Modules\ProductService\Entities\ProductService::whereIn('id',$product)->get()->pluck('name')->toArray();
                    if(count($product) > 0)
                    {
                        $product_name = implode(',',$product);
                    }
                    $proposal->product = $product_name;
                }
                if($proposal->customer_id){
                    $customer = User::find($proposal->customer_id);
                    $proposal->customer_name = $customer->name;
                }
                if($proposal->category_id){
                    $category = \Modules\ProductService\Entities\Category::where('id',$proposal->category_id)->where('type', 1)->first();
                    $proposal->category_name = $category->name;
                }
            }
            $action = 'New Proposal';
            $module = 'general';

            $pabbly_array = array(
                "issue_date" => $proposal['issue_date'],
                "Items"      => $request->items,
            );
            PabblySend::SendPabblyCall($module ,$pabbly_array, $action);
        }
    }
}