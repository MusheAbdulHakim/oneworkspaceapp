<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WorkSpace;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\Api\AuthApiController;

class CustomApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            
            JWTAuth::parseToken()->authenticate();
            $request['user_id'] = JWTAuth::user()->id;
			$module = $request->segment(2);
            
			if($module){
				$module_status = module_is_active($module, creatorId());
				if($module_status != true)
				{
					return response()->json(['status' => 1, 'message' => 'Your Add-on Is Not Activated!'], 401);
				}
				$request['module_name'] = $module;
			}
            
			$request->route()->forgetParameter('module');

        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['status' => 0, 'message' => 'Token has expired'], 401);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => 0, 'message' => 'Token is invalid'], 401);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => 0, 'message' => 'Token is absent'], 401);
        }

        return $next($request);
    }
}





