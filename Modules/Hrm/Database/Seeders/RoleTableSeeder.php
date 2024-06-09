<?php

namespace Modules\Hrm\Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Hrm\Entities\Employee;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $super_admin = User::where('type', 'super admin')->first();
        if (!empty($super_admin)) {
            $companys = User::where('type', 'company')->get();
            if (count($companys) > 0) {
                foreach ($companys as $key => $company) {
                    $role = Role::where('name', 'hr')->where('created_by', $company->id)->where('guard_name', 'web')->exists();
                    if (!$role) {
                        $role                   = new Role();
                        $role->name             = 'hr';
                        $role->guard_name       = 'web';
                        $role->module           = 'Hrm';
                        $role->created_by       = $company->id;
                        $role->save();
                    }
                }
            }
        }

        Employee::GivePermissionToRoles();
        Employee::defaultdata();
    }
}
