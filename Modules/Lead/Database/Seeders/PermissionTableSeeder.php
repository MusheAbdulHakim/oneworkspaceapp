<?php

namespace Modules\Lead\Database\Seeders;

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
        $module = 'Lead';

        $permissions = [
            'crm manage',
            'crm dashboard manage',
            'crm setup manage',
            'crm report manage',
            'lead manage',
            'lead create',
            'lead edit',
            'lead delete',
            'lead show',
            'lead move',
            'lead import',
            'lead call create',
            'lead call edit',
            'lead call delete',
            'lead email create',
            'lead to deal convert',
            'lead report',
            'deal report',
            'deal manage',
            'deal create',
            'deal edit',
            'deal delete',
            'deal show',
            'deal move',
            'deal import',
            'deal task create',
            'deal task edit',
            'deal task delete',
            'deal task show',
            'deal call create',
            'deal call edit',
            'deal call delete',
            'deal email create',
            'pipeline manage',
            'pipeline create',
            'pipeline edit',
            'pipeline delete',
            'dealstages manage',
            'dealstages create',
            'dealstages edit',
            'dealstages delete',
            'leadstages manage',
            'leadstages create',
            'leadstages edit',
            'leadstages delete',
            'labels manage',
            'labels create',
            'labels edit',
            'labels delete',
            'source manage',
            'source create',
            'source edit',
            'source delete',
            'lead task create',
            'lead task edit',
            'lead task delete',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module',$module)->exists();
            if($table == false)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => $module,
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
