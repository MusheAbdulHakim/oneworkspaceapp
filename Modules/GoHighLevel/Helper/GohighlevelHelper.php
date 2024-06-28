<?php
namespace Modules\GoHighLevel\Helper;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\GoHighLevel\Entities\Gohighlevel as GHLEntity;
use Modules\GoHighLevel\Entities\SubAccount;
use \MusheAbdulHakim\GoHighLevel\GoHighLevel;

class GohighlevelHelper
{

    public $access;
    public $ghlClient;


    public function __construct(){
        $this->access = GHLEntity::first();
        if(!empty($this->access)){
            $this->ghlClient = GoHighLevel::init($this->access->access_token);
        }
    }

    public function generateCompanyToken($code){
        $client_id = env("GHL_CLIENT_ID");
        $client_secret = env("GHL_CLIENT_SECRET");
        $callback_url = env('GHL_CALLBACK_URL');
        return GoHighLevel::getAccessToken('https://services.leadconnectorhq.com/oauth/token', 'application/x-www-form-urlencoded', [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'authorization_code',
            'user_type' => 'Company',
            'code' => $code,
            'redirect_uri' => $callback_url,
        ]);

    }

    public function createSubAccount(User $user, $request){
        try {
            $resource = $this->ghlClient->withVersion('2021-07-28')->make();
            $companyId = $this->access->companyId;
            $names = explode(',', $user->name);
            $location = $resource->location()->create([
                'name' => $user->name,
                'companyId' => $companyId,
                'email' => $user->email,
            ]);
            if(is_array($location) && (count($location) > 0)){
                $ghluser = $resource->user()->create($companyId,[
                    'firstName' => $names[0] ?? '',
                    'lastName' => $names[1] ?? '',
                    'email' => $user->email,
                    'password' => $request->password,
                    'type' => 'account',
                    'role' => 'admin',
                    'locationIds' => [$location['id']],
                    'permissions' => [
                        "campaignsEnabled" => true,
                        "campaignsReadOnly" => true,
                        "contactsEnabled" => true,
                        "workflowsEnabled" => true,
                        "workflowsReadOnly" => true,
                        "triggersEnabled" => true,
                        "funnelsEnabled" => true,
                        "websitesEnabled" => true,
                        "opportunitiesEnabled" => true,
                        "dashboardStatsEnabled" => true,
                        "bulkRequestsEnabled" => true,
                        "appointmentsEnabled" => true,
                        "reviewsEnabled" => true,
                        "onlineListingsEnabled" => true,
                        "phoneCallEnabled" => true,
                        "conversationsEnabled" => true,
                        "assignedDataOnly" => true,
                        "adwordsReportingEnabled" => true,
                        "membershipEnabled" => true,
                        "facebookAdsReportingEnabled" => true,
                        "attributionsReportingEnabled" => true,
                        "settingsEnabled" => true,
                        "tagsEnabled" => true,
                        "leadValueEnabled" => true,
                        "marketingEnabled" => true,
                        "agentReportingEnabled" => true,
                        "botService" => true,
                        "socialPlanner" => true,
                        "bloggingEnabled" => true,
                        "invoiceEnabled" => true,
                        "affiliateManagerEnabled" => true,
                        "contentAiEnabled" => true,
                        "refundsEnabled" => true,
                        "recordPaymentEnabled" => true,
                        "cancelSubscriptionEnabled" => true,
                        "paymentsEnabled" => true,
                        "communitiesEnabled" => true,
                        "exportPaymentsEnabled" => true
                    ],
                ]);
                SubAccount::create([
                    'gohighlevel_id' => $this->access->id,
                    'user_id' => $user->id,
                    'workspace' => getActiveWorkSpace($user->id) ?? null,
                    'snapshot' => null,
                    'social' => null,
                    'permissions' => $ghluser['permissions'] ?? null,
                    'scopes' => null,
                    'locationId' => $location['id'] ?? null,
                    'ghl_user_id' => $ghluser['id'] ?? null
                ]);
                $enableSaas = $this->ghlClient->withVersion('2021-04-15')->make()
                    ->Saas()->enable($location['id']);
                return true;
            }
        }catch(\Exception $e){
            Log::alert($e->getMessage());
            return false;
        }
    }

    public function locations(){

    }

    public function enableSaas($locationId){

        $location = $this->ghlClient->withVersion('2021-07-28')
                    ->make()
                    ->location()
                    ->get($locationId);

        return $this->ghlClient->withVersion('2021-04-15')
        ->withHttpHeader('channel','OAUTH')
        ->withHttpHeader('source','INTEGRATION')
        ->make()->Saas()->update($locationId);
    }

    public function permissionsObject(){
        $permissions = [
                "campaignsEnabled" => true,
                "campaignsReadOnly" => true,
                "contactsEnabled" => true,
                "workflowsEnabled" => true,
                "workflowsReadOnly" => true,
                "triggersEnabled" => true,
                "funnelsEnabled" => true,
                "websitesEnabled" => true,
                "opportunitiesEnabled" => true,
                "dashboardStatsEnabled" => true,
                "bulkRequestsEnabled" => true,
                "appointmentsEnabled" => true,
                "reviewsEnabled" => true,
                "onlineListingsEnabled" => true,
                "phoneCallEnabled" => true,
                "conversationsEnabled" => true,
                "assignedDataOnly" => true,
                "adwordsReportingEnabled" => true,
                "membershipEnabled" => true,
                "facebookAdsReportingEnabled" => true,
                "attributionsReportingEnabled" => true,
                "settingsEnabled" => true,
                "tagsEnabled" => true,
                "leadValueEnabled" => true,
                "marketingEnabled" => true,
                "agentReportingEnabled" => true,
                "botService" => true,
                "socialPlanner" => true,
                "bloggingEnabled" => true,
                "invoiceEnabled" => true,
                "affiliateManagerEnabled" => true,
                "contentAiEnabled" => true,
                "refundsEnabled" => true,
                "recordPaymentEnabled" => true,
                "cancelSubscriptionEnabled" => true,
                "paymentsEnabled" => true,
                "communitiesEnabled" => true,
                "exportPaymentsEnabled" => true
        ];
        return (object)json_encode($permissions, JSON_FORCE_OBJECT);
    }

    public function deleteSubAccount($id){
        try{
            return $this->ghlClient->withVersion('2021-07-28')
                ->make()->location()->delete($id);
        }catch(\Exception $e){
            Log::error($e->getMessage());
        }
    }



    public function getUsers($locationId){
        return $this->ghlClient->withVersion('2021-07-28')->make()
            ->user()->byLocation($locationId);
    }

}
