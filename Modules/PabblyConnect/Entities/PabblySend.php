<?php

namespace Modules\PabblyConnect\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PabblySend extends Model
{
    use HasFactory;

    protected $fillable = [];

    static public function SendPabblyCall($module, $parameter, $action, $workspace_id = null)
    {
        $pabbly_Id = PabblyModules::where('module', $module)->where('submodule', $action)->first();

        if (!empty($pabbly_Id)) {
            if (!empty($workspace_id)) {
                $pabbly = Pabbly::where('action', $pabbly_Id->id)->where('workspace', '=', $workspace_id)->first();
            } else {
                $pabbly = Pabbly::where('action', $pabbly_Id->id)->first();
            }

            if (!empty($pabbly)) {

                $url = $pabbly->url;

                // $_ZAP_ARRAY = ;
                $_ZAP_ARRAY = json_encode($parameter);

                $ZAPIER_HOOK_URL = $url;

                if (!empty($url) && !empty($parameter)) {
                    try {
                        $ch = curl_init($ZAPIER_HOOK_URL);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $_ZAP_ARRAY);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                        $response = curl_exec($ch);
                        
                    } catch (\Throwable $th) {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected static function newFactory()
    {
        return \Modules\PabblyConnect\Database\factories\PabblySendFactory::new();
    }
}
