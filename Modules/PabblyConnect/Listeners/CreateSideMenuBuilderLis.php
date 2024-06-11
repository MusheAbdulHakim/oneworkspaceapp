<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\PabblyConnect\Entities\PabblySend;
use Modules\SideMenuBuilder\Entities\SideMenuBuilder;
use Modules\SideMenuBuilder\Events\CreateSideMenuBuilder;

class CreateSideMenuBuilderLis
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
    public function handle(CreateSideMenuBuilder $event)
    {
        if (module_is_active('PabblyConnect')) {
            $menu_builder = $event->menu_builder;

            $parent = SideMenuBuilder::find($menu_builder->parent_id);

            $pabbly_array = [
                'Manu Type' => $menu_builder->menu_type,
                'Title' => $menu_builder->name,
                'Link Type' => $menu_builder->link_type,
                'Parent' => isset($parent->name) ? $parent->name : '',
                'Link' => $menu_builder->link,
                'Window Type' => $menu_builder->window_type,
            ];

            $action = 'New Side Menu';
            $module = 'SideMenuBuilder';
            PabblySend::SendPabblyCall($module, $pabbly_array, $action);
        }
    }
}
