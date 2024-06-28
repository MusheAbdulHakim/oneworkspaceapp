<?php

namespace Modules\GoHighLevel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\GoHighLevel\Entities\SubAccount;
use Modules\GoHighLevel\Entities\SubaccountToken;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class ApiKeyIsNotExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(auth()->check()){
            $user = $request->user();
            $helper = (new GohighlevelHelper());
            $client = $helper->ghlClient;

            $subaccount = SubAccount::where('user_id', $user->id)
                    ->where('workspace',$user->workspace_id)
                    ->first();

            if(!empty($helper->access) && !empty($client)){

                $subaccount = SubAccount::where('user_id', $user->id)
                    ->where('workspace',$user->workspace_id)
                    ->first();

                if(!empty($subaccount) && !empty($subaccount->token) && api_key_expired($subaccount->token->expires_in)){
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
        return $next($request);
    }
}
