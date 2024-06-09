<?php

namespace Modules\Pos\Database\Seeders;

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

        $permissions  = [
            'pos manage',
            'pos show',
            'pos dashboard manage',
            'pos add manage',
            'pos cart manage',
            // 'warehouse manage',
            // 'warehouse create',
            // 'warehouse edit',
            // 'warehouse delete',
            // 'warehouse show',
            // 'warehouse import',
            // 'purchase manage',
            // 'purchase create',
            // 'purchase edit',
            // 'purchase delete',
            // 'purchase show',
            // 'purchase send',
            // 'purchase payment create',
            // 'purchase payment delete',
            // 'purchase product delete',
            // 'report warehouse',
            // 'report purchase',
            'report pos',
            'report pos vs expense',
            // 'purchase debitnote create',
            // 'purchase debitnote edit',
            // 'purchase debitnote delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $table = Permission::where('name',$value)->exists();
            if($table == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'Pos',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
