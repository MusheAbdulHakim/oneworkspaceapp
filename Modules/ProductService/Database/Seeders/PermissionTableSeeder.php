<?php

namespace Modules\ProductService\Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');
        $permission  = [
            'product&service manage',
            'product&service create',
            'product&service edit',
            'product&service delete',
            'product&service import',
            'unit manage',
            'unit cerate',
            'unit edit',
            'unit delete',
            'tax manage',
            'tax create',
            'tax edit',
            'tax delete',
            'category manage',
            'category create',
            'category edit',
            'category delete',
            'product service manage',
        ];
        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','ProductService')->exists();
            if(!$table)
            {
                Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'ProductService',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                $permission = Permission::where('name',$value)->first();
                $company_role->givePermission($permission);
            }
        }
    }
}
