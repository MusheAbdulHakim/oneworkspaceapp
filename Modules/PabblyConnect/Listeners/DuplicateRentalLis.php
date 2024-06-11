<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\ProductService;
use Modules\RentalManagement\Entities\RentalProduct;
use Modules\RentalManagement\Events\DuplicateRental;

class DuplicateRentalLis
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
    public function handle(DuplicateRental $event)
    {
        if (module_is_active('PabblyConnect')) {
            $duplicateRental = $event->duplicateRental;

            $render_product = RentalProduct::where('rental_id', $duplicateRental->rental_id)->get();
            $customer = User::find($duplicateRental->customer_id);

            $itemDataArray = [];

            foreach ($render_product as $product) {
                $item = ProductService::find($product->product_id);

                if ($item) {
                    $itemDataArray[] = [
                        'Name' => $item->name,
                        'SKU' => $item->sku,
                        'Description' => $item->description,
                        'Quantity' => $product->quantity,
                        'Price' => $product->price,
                    ];
                }
            }

            $pabbly_array = [
                'Customer Name' => $customer->name,
                'Customer Email' => $customer->email,
                'Rent Item' => $itemDataArray,
                'Start Date' => $duplicateRental->start_date,
                'End Date' => $duplicateRental->end_date,
            ];

            $action = 'Duplicate Rental';
            $module = 'RentalManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
