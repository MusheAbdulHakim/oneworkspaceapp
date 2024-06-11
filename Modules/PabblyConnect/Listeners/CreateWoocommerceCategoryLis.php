<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\ProductService\Entities\Category;
use Modules\WordpressWoocommerce\Events\CreateWoocommerceCategory;

class CreateWoocommerceCategoryLis
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
    public function handle(CreateWoocommerceCategory $event)
    {
        if (module_is_active('PabblyConnect')) {
            $request = $event->request;
            $category = $event->Category;

            $category_data = Category::find($category->original_id);

            $pabbly_array = [
                'Title' => $category_data->name,
                "Color" => $category_data->color
            ];
            $action = 'New Product Category';
            $module = 'WordpressWoocommerce';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
