<?php

namespace Modules\GoHighLevel\Listeners;

use App\Events\DestroyUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GoHighLevel\Entities\SubAccount;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class DeleteSubAccountListener
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
    public function handle(DestroyUser $event)
    {
        $user = $event->user;
        if(!empty($user)){
            $helper = new GohighlevelHelper();
            $subaccount = SubAccount::where('user_id', $user->id)
            ->where('workspace',$user->workspace_id)->first();
            if(!empty($subaccount)){
                $helper->deleteSubAccount($subaccount->locationId);
                $subaccount->delete();
            }
        }
    }
}
