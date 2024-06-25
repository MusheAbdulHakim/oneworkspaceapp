<?php
namespace Modules\GoHighLevel\Helper;

use App\Models\User;
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

    public function createUser(User $user){
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
                'password' => $user->password,
                'type' => 'account',
                'role' => 'admin',
                'locationIds' => [$location['id']],
                'permissions' => json_encode([
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
                ]),
            ]);
            SubAccount::create([
                'gohighlevel_id' => $this->access->id,
                'user_id' => $user->id,
                'workspace' => $user->workspace_id ?? null,
                'snapshot' => null,
                'social' => null,
                'permissions' => $location['permissions'] ?? null,
                'scopes' => null,
                'locationId' => $location['id'] ?? null,
                'ghl_user_id' => $ghluser['id'] ?? null
            ]);
        }
    }

    public function deleteUser($id){
        $resource = $this->ghlClient->withVersion('2021-07-28')->make()->user();
        return $resource->delete($id);
    }

}
