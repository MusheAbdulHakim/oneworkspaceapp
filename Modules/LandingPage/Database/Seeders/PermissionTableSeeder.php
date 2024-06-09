<?php

namespace Modules\LandingPage\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
     public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $module = 'LandingPage';

        $permissions  = [
            'landingpage manage',
            'landingpage create',
            'landingpage edit',
            'landingpage store',
            'landingpage update',
            'landingpage delete',
            'marketplace manage',
            'marketplace create',
            'marketplace edit',
            'marketplace store',
            'marketplace update',
            'marketplace delete',
        ];

        $super_admin = Role::where('name','super admin')->first();

        foreach ($permissions as $key => $value) {
            $check = Permission::where('name', $value)->where('module', 'LandingPage')->exists();
            if (!$check) {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'LandingPage',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$super_admin->hasPermission($value))
                {
                    $super_admin->givePermission($permission);
                }
            }
        }
    }
}
