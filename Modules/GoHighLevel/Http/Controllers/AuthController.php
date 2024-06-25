<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoHighLevel\Entities\Gohighlevel as GoHighLevelEntity;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class AuthController extends Controller
{
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
            $ghlHelper = new GohighlevelHelper();
            $response = $ghlHelper->generateCompanyToken($code);
            if (is_array($response)) {
                $ghlAccess = auth()->user()->gohighlevel;
                if (!empty($ghlAccess)) {
                    $ghlAccess->access_token = $response['access_token'];
                    $ghlAccess->access_expires_in = $response['expires_in'];
                    $ghlAccess->token_type = $response['token_type'];
                    $ghlAccess->userType = $response['userType'];
                    $ghlAccess->companyId = $response['companyId'];
                    $ghlAccess->locationId = isset($response['locationId']) ? $response['locationId']: null;
                    $ghlAccess->userId = $response['userId'];
                    $ghlAccess->user_id = auth()->user()->id;
                    $ghlAccess->workspace = getActiveWorkSpace();
                    $ghlAccess->save();
                    return redirect()->route("home")->with('success', __('Your gohighlevel account token has been updated.'));
                } else {
                    GoHighLevelEntity::create([
                        'access_token' => $response['access_token'],
                        'access_expires_in' => $response['expires_in'],
                        'token_type' => $response['token_type'] ?? null,
                        'userType' => $response['userType'] ?? null,
                        'companyId' => $response['companyId'] ?? null,
                        'locationId' => isset($response['locationId']) ? $response['locationId']: null,
                        'userId' => $response['userId'] ?? null,
                        'user_id' => auth()->user()->id,
                        'workspace' => getActiveWorkSpace(),
                    ]);
                    return redirect()->route("home")->with('success', __('Your gohighlevel account has been linked.'));
                }
            }
            return redirect()->route("settings.index")->with('error', __('Account Not linked.'));
        } catch (\Exception $e) {
            // throw $e;
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
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

}
