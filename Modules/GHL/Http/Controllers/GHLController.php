<?php

namespace Modules\GHL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Modules\GHL\Entities\GhlIntegration;
use Modules\GHL\Traits\UserGHL;
use MusheAbdulHakim\GoHighLevel;


class GHLController extends Controller
{

    use UserGHL;

    public function redirect()
    {
        $auth_url = env("GHL_AUTH_URL");
        $scopes = env("GHL_API_SCOPES");
        $client_id = env("GHL_CLIENT_ID");
        $callback_url = env('GHL_CALLBACK_URL');
        $authUrl = "{$auth_url}/oauth/chooselocation?response_type=code&redirect_uri={$callback_url}&client_id={$client_id}&scope={$scopes}";

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {

        try {
            $code = $request->code;
            $client_id = env("GHL_CLIENT_ID");
            $client_secret = env("GHL_CLIENT_SECRET");
            $callback_url = env('GHL_CALLBACK_URL');
            $params = [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $callback_url
            ];
            $response = $this->generateToken($code, $params);
            // dd($response);
            if (is_array($response)) {
                $ghlAccess = auth()->user()->ghl;

                if (!empty($ghlAccess)) {
                    $ghlAccess->access_token = $response['access_token'];
                    $ghlAccess->access_expires_in = $response['expires_in'];
                    $ghlAccess->token_type = $response['token_type'];
                    $ghlAccess->userType = $response['userType'];
                    $ghlAccess->companyId = $response['companyId'];
                    $ghlAccess->locationId = $response['locationId'];
                    $ghlAccess->userId = $response['userId'];
                    $ghlAccess->user_id = auth()->user()->id;
                    $ghlAccess->workspace = getActiveWorkSpace();
                    $ghlAccess->save();
                    return redirect()->route("home")->with('success', __('Your gohighlevel account token has been updatd.'));
                } else {
                    $ghl = new GhlIntegration();
                    $ghl->access_token = $response['access_token'];
                    $ghl->access_expires_in = $response['expires_in'];
                    $ghl->token_type = $response['token_type'];
                    $ghl->userType = $response['userType'];
                    $ghl->companyId = $response['companyId'];
                    $ghl->locationId = $response['locationId'];
                    $ghl->userId = $response['userId'];
                    $ghl->user_id = auth()->user()->id;
                    $ghl->workspace = getActiveWorkSpace();
                    $ghl->save();
                }
                return redirect()->route("home")->with('success', __('Your gohighlevel account has been linked.'));
            }
            return redirect()->route("settings.index")->with('error', __('Account Not linked.'));
        } catch (\Exception $e) {
            return redirect()->route("home")->with('error', $e->getMessage());
        }
    }


    public function generateToken(string $code, $params = [])
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => env('GHL_API_URL')
        ]);
        $response = $client->request('POST', 'https://services.leadconnectorhq.com/oauth/token', [
            'form_params' => $params,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer 123',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }


    public function dashboard()
    {
        try {
            $ghl = $this->initGHL();
            if (!empty($ghl)) {
                $locationId = $this->userGHL()->locationId;
                $contacts = $ghl->withVersion('2021-07-28')
                    ->make()
                    ->contacts()->list($locationId);
                $invoices = $ghl->withVersion('2021-07-28')
                    ->make()->invoices()
                    ->list($locationId, 'location', 100, 0);
                $funnels = $ghl->withVersion('2021-07-28')
                    ->make()->funnels()->list($locationId, [
                        'locationId' => $locationId
                    ]);

                $calendars = $ghl->withVersion('2021-04-15')
                    ->make()
                    ->calendars()
                    ->list($locationId);
                $start = now()->startOfWeek(Carbon::TUESDAY)->valueOf();
                $end = now()->endOfWeek(Carbon::MONDAY)->valueOf();
                // $events = $ghl->withVersion('2021-04-15')
                //                 ->make()->calendars()
                //                 ->events()->get($end, $start,$locationId,'',[
                //                     'userId' => $this->userGHL()->userId,
                //                     'endTime' => $end,
                //                 ]);
                return view('ghl::dashboard.dashboard', compact(
                    'contacts',
                    'invoices',
                    'calendars',
                    'funnels'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Please authenticate your ghl account to continue');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
