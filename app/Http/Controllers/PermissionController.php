<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Nwidart\Modules\Facades\Module;

class PermissionController extends Controller
{


    public function index()
    {
        if(Auth::user()->isAbleTo('permission manage'))
        {
            $permissions = Permission::all();
            $modules = array_merge(['General'],getModuleList());
            return view('permission.index',compact('permissions','modules'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $roles = Role::get();
        $modules = array_merge(['General'],getModuleList());
        return view('permission.create',compact('modules','roles'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request, [
                'name' => 'required|max:40',
                'module'=> 'required'
            ]
        );

        $name             = $request['name'];
        $permission       = new Permission();
        $permission->name = $name;
        $permission->module = $request['module'];

        $roles = $request['roles'];

        $permission->save();

        if(!empty($request['roles']))
        {
            foreach($roles as $role)
            {
                $r          = Role::where('id', '=', $role)->firstOrFail();
                $permission = Permission::where('name', '=', $name)->first();
                if(!$r->hasPermission($name))
                {
                    $r->givePermission($permission);
                }
            }
        }

        return redirect()->route('permissions.index')->with(
            'success', 'Permission ' . $permission->name . ' added!'
        );


    }


    public function edit(Permission $permission)
    {

        $roles = Role::where('created_by', '=', \Auth::user()->id)->get();
        $modules = array_merge(['General'],getModuleList());
        return view('permission.edit', compact('roles', 'permission','modules'));


    }


    public function update(Request $request, Permission $permission)
    {

        $permission = Permission::findOrFail($permission['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:40',
                    ]
        );
        $input = $request->all();
        $permission->fill($input)->save();

        return redirect()->route('permissions.index')->with(
            'success', 'Permission ' . $permission->name . ' updated!'
        );


    }

    public function destroy($id)
    {

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')->with( 'success', 'Permission successfully deleted.' );

    }
}
