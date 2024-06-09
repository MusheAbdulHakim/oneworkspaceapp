<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Attendance;
use Modules\Hrm\Entities\Employee;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\WorkSpace;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Registered;
use Lab404\Impersonate\Impersonate;

class AuthApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.api.auth', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        try{
            $validator = \Validator::make(
                $request->all(), [
                    'email' => 'required|string|email',
                    'password' => 'required|string',
                    // 'module_name' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['status'=>0, 'message'=>$messages->first()]);
            }
            
            // $request['type']    = 'staff';
            $credentials = $request->only('email', 'password');
            
            $token = JWTAuth::attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid Credentials',
                ], 401);
            }

            // $AuthUser       = JWTAuth::user();
            // $module_name = $request->route()->parameter('module');
            
            // $module_status = module_is_active($module_name, creatorId());
            // if($module_status != true)
            // {
            //     return response()->json(['status' => 'error', 'message' => 'Your Add-on Is Not Activated!'], 401);
            // }

            return response()->json([
                    'status' => 1,
                    'data' => [
                        'token'         => $token,
                        'user'          => $this->getUserArray(),
                        'workspaces'    => $this->getWorkspaceArray(),
                    ], // Include the user data in the response
                ],200);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 
    }

    public function logout()
    {
        \Auth::logout();
        return response()->json([
            'status' => 1,
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        try{
            return response()->json([
                'status'    => 1,
                'data' => [
                    'token'         => JWTAuth::refresh(),
                    'user'          => $this->getUserArray(),
                    'workspaces'    => $this->getWorkspaceArray(),
                ], // Include the user data in the response
            ]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        } 

    }

    public function editProfile(Request $request)
    {
        try{

            if($request->user_id){

                $user = User::find($request->user_id);
                
            }elseif(\Auth::user()){
                
                $user =  \Auth::user();
            }

            $validator = \Validator::make(
                $request->all(), [
                        'name' => 'required|string',
                        'mobile_no' => 'required|string',
                        'email' => ['required',
                                        Rule::unique('users')->where(function ($query)  use ($user) {
                                            return $query->whereNotIn('id',[$user->id])->where('created_by', $user->created_by)->where('workspace_id',$user->workspace_id);
                                        })
                                    ],
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['status'=>0, 'message'=>$messages->first()]);
            }

            if($user){

                if ($request->hasFile('profile'))
                {

                    $filenameWithExt = $request->file('profile')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('profile')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $userAuth = $user;
                    $path = upload_file($request,'profile',$fileNameToStore,'users-avatar');

                    if($path['flag'] == 0){
                        return response()->json(['status'=>0, 'message'=>$path['msg']]);
                    }

                    // old img delete
                    if(!empty($userAuth['avatar']) && strpos($userAuth['avatar'],'avatar.png') == false && check_file($userAuth['avatar']))
                    {
                        delete_file($userAuth['avatar']);
                    }
                }

                if (!empty($request->profile) && isset($path['url']))
                {
                    $user->avatar =  $path['url'];
                }
	

                $user->name         = $request->name;
                $user->email        = $request->email;
                $user->mobile_no    = $request->mobile_no;
                $user->save();
        
                $employee = Employee::where('user_id',$user->id)->first();
                $employee->phone = $request->mobile_no;
                $employee->name = $request->name;
                $employee->email = $request->email;
                $employee->save();

                return response()->json(['status'=>1,'message'=>'profile updated successfully.','data'=> $this->getUserArray($user->id)]);
            }

            return response()->json(['status'=>0,'message'=>'User Not Found!!!']);

        } catch (\Exception $e) {
			
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        }    

    }

    public function getWorkspaceArray($user_id = null)
    {
        if($user_id != null){

            $user = User::find($user_id);
            
        }elseif(\Auth::user()){
            
            $user = \Auth::user();
        }

        $users      = User::where('email', $user->email)->get();
        return      WorkSpace::whereIn('id', $users->pluck('workspace_id')->toArray())
                                    ->orWhereIn('created_by', $users->pluck('id')->toArray())
                                    ->where('is_disable', 1)
                                    ->get()
                                    ->map(function($workspace){
                                        return [
                                            'id'            => $workspace->id,
                                            'name'          => $workspace->name,
                                            'slug'          => $workspace->slug,
                                            'status'        => $workspace->status,
                                            'created_by'    => $workspace->created_by,
                                        ];
                                    });
    }

    public function getUserArray($user_id = null)
    {
	
        if($user_id != null){

            $user = User::find($user_id);
            
        }elseif(\Auth::user()){
            
            $user =  \Auth::user();
        }
		
        return   [
                        'id'                => $user->id,
                        'name'              => $user->name,
                        'email'             => $user->email,
                        'mobile_no'         => $user->mobile_no,
                        'type'              => $user->type,
                        'active_workspace'  => $user->active_workspace,
                        'avatar'            => check_file($user->avatar) ? get_file($user->avatar) : get_file('uploads/users-avatar/avatar.png'),
                        'lang'              => $user->lang,
                    ];

    }

    public function changePassword(Request $request)
    {
        try{

            $validator = \Validator::make(
                $request->all(), [
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return response()->json(['status'=>0, 'message'=>$messages->first()]);
            }
            
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['error' => 'The provided current password does not match our records.'], 422);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['status'=>1,'message'=>'password updated successfully.','data'=> $this->getUserArray()]);

        } catch (\Exception $e) {
            return response()->json(['status'=>0,'message'=>'something went wrong!!!']);
        }                             

    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $emp = Employee::where('user_id', '=', $user->id)->delete();
        // get all table
        $tables_in_db = \DB::select('SHOW TABLES');
        $db = "Tables_in_".env('DB_DATABASE');
        foreach($tables_in_db as $table)
        {
            if (Schema::hasColumn($table->{$db}, 'created_by'))
            {
                \DB::table($table->{$db})->where('created_by', $user->id)->delete();
            }
        }

        // $user->delete();

        return response()->json(['status'=>1,'message'=>'account deleted successfully.']);
    }


    // public function workspaceChange(Request $request)
    // {
    //     $check = WorkSpace::find($request->workspace_id);
    //     if(!empty($check))
    //     {
    //         $users = User::where('email',\Auth::user()->email)->where('workspace_id',$workspace_id)->where('created_by',Auth::user()->created_by)->first();
    //         if(empty($users))
    //         {
    //             $users = User::where('email',\Auth::user()->email)->Where('id',$check->created_by)->first();
    //         }
    //         if(empty($users))
    //         {
    //             $users = User::where('email',\Auth::user()->email)->where('workspace_id',$workspace_id)->first();
    //         }
    //         $user = User::find($users->id);
    //         $user->active_workspace = $workspace_id;
    //         $user->save();
    //         if(!empty($user)){
    //             Auth::login($user);
    //             return redirect()->route('dashboard')->with('success', 'User Workspace change successfully.');
    //         }
    //         return redirect()->route('dashboard')->with('success', 'User Workspace change successfully.');
    //     }else{
    //        return redirect()->route('dashboard')->with('error', "Workspace not found.");
    //     }
    // }

    public function getWorkspaceUsers(Request $request)
    {
        try {
            
            $validator = \Validator::make(
                $request->all(), [
                    'workspace_id'  => 'required',
                ]
            );
    
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
    
                return response()->json(['status' => 0 , 'message' => $messages->first()], 403);
            }

            $objUser          = Auth::user();
            $currentWorkspace = $request->workspace_id;

            $users  = User::where('created_by', creatorId())
                                    ->emp()
                                    ->where('workspace_id', $currentWorkspace)
                                    ->orWhere('id', Auth::user()->id)
                                    ->limit($request->limit??10)->offset((($request->page??1)-1)*$request->limit??10)
                                    ->get()
                                    ->map(function($user){
                                            return [
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                            ];
                                        });
            
            return response()->json([
                'status' => 1,
                'data'   => $users,
                
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 0 ,'message' => 'something went wrong!!!']);
        } 
    }
    
}
