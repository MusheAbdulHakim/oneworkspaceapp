<?php

namespace Modules\Account\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Account\Entities\TransactionLines;
use Modules\Account\Events\DestroyRevenue;

class RevenueDestroy
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
    public function handle(DestroyRevenue $event)
    {
        $revenue = $event->revenue;
        TransactionLines::where('reference','Revenue')->where('reference_id',$revenue->id)->where('reference_sub_id',$revenue->id)->delete();

    }
}
