<?php

namespace Modules\GoHighLevel\Listeners;

use App\Events\CustomLoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\GoHighLevel\Entities\SubAccount;
use Modules\GoHighLevel\Entities\SubaccountToken;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class UserLoginListener
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
    public function handle(CustomLoginEvent $event)
    {
        $user = $event->user;
        $helper = (new GohighlevelHelper());
        $client = $helper->ghlClient;

        if(!empty($helper->access) && !empty($client)){

            $subaccount = SubAccount::where('user_id', $user->id)
                ->where('workspace',$user->workspace_id)
                ->first();
            if(!empty($subaccount) && !empty($subaccount->locationId)){
                $apiTokenResponse = $client->withVersion('2021-07-28')
                    ->make()
                    ->OAuth()
                    ->AcessFromAgency($helper->access->companyId, $subaccount->locationId);

                SubaccountToken::updateOrCreate([
                    'user_id' => $user->id,
                    'workspace' => getActiveWorkSpace($user->id),
                    'sub_account_id' => $subaccount->id,
                ],[
                    'sub_account_id' => $subaccount->id,
                    'access_token' => $apiTokenResponse['access_token'] ?? null,
                    'token_type' => $apiTokenResponse['token_type'] ?? null,
                    'expires_in' => $apiTokenResponse['expires_in'] ?? null,
                    'refresh_token' => $apiTokenResponse['refresh_token'] ?? null,
                    'scope' => $apiTokenResponse['scope'] ?? null,
                    'userType' => $apiTokenResponse['userType'] ?? 'Location',
                    'companyId' => $apiTokenResponse['companyId'] ?? $subaccount->companyId,
                    'locationId' => $apiTokenResponse['locationId'] ?? null,
                    'userId' => $apiTokenResponse['userId'] ?? null,
                    'traceId' => $apiTokenResponse['traceId'] ?? null,
                    'user_id' => $user->id,
                    'workspace' => getActiveWorkSpace($user->id) ?? 0
                ]);
            }
        }

    }
}
