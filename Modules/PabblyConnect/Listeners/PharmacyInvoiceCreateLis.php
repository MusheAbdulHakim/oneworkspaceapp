<?php

namespace Modules\PabblyConnect\Listeners;

use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\PharmacyManagement\Entities\Medicine;
use Modules\PharmacyManagement\Entities\MedicineCategory;
use Modules\PharmacyManagement\Entities\MedicineType;
use Modules\PharmacyManagement\Events\PharmacyInvoiceCreate;

class PharmacyInvoiceCreateLis
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
    public function handle(PharmacyInvoiceCreate $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $pharmacyInvoice = $event->pharmacyInvoice;

            $customer = User::find($pharmacyInvoice->user_id);

            $items = $request->items;

            $categoryIds = array_unique(array_column($items, 'category'));
            $typeIds = array_unique(array_column($items, 'type'));
            $medicineIds = array_unique(array_column($items, 'medicine'));

            $categoryNames = MedicineCategory::whereIn('id', $categoryIds)->pluck('name', 'id')->toArray();
            $typeNames = MedicineType::whereIn('id', $typeIds)->pluck('name', 'id')->toArray();
            $medicineNames = Medicine::whereIn('id', $medicineIds)->pluck('name', 'id')->toArray();

            $pharmacyInvoiceItems = array_map(function ($item) use ($categoryNames, $typeNames, $medicineNames) {
                return [
                    'category' => $categoryNames[$item['category']] ?? null,
                    'type' => $typeNames[$item['type']] ?? null,
                    'medicine' => $medicineNames[$item['medicine']] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'description' => $item['description'],
                ];
            }, $items);

            $pabbly_array = [
                'Customer Name' => $customer->name,
                'Customer Email' => $customer->email,
                'Invoice Issue Date' => $pharmacyInvoice->issue_date,
                'Invoice Due Date' => $pharmacyInvoice->due_date,
                'Items' => $pharmacyInvoiceItems,
            ];

            $action = 'New Pharmacy Invoice';
            $module = 'PharmacyManagement';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
