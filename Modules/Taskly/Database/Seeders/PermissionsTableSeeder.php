<?php

namespace Modules\Taskly\Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Model;

class PermissionsTableSeeder extends Seeder
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
        $permissions = [
            'taskly manage',
            'taskly setup manage',
            'taskly dashboard manage',
            'project manage',
            'project create',
            'project edit',
            'project delete',
            'project show',
            'project invite user',
            'project report manage',
            'project import',
            'project setting',
            'team member remove',
            'team client remove',
            'bug manage',
            'bug create',
            'bug edit',
            'bug delete',
            'bug show',
            'bug move',
            'bug comments create',
            'bug comments delete',
            'bug file uploads',
            'bug file delete',
            'bugstage manage',
            'bugstage edit',
            'bugstage delete',
            'bugstage show',
            'milestone manage',
            'milestone create',
            'milestone edit',
            'milestone delete',
            'milestone show',
            'task manage',
            'task create',
            'task edit',
            'task delete',
            'task show',
            'task move',
            'task file manage',
            'task file uploads',
            'task file delete',
            'task file show',
            'task comment manage',
            'task comment create',
            'task comment edit',
            'task comment delete',
            'task comment show',
            'taskstage manage',
            'taskstage edit',
            'taskstage delete',
            'taskstage show',
            'sub-task manage',
            'sub-task create',
            'sub-task edit',
            'sub-task delete',

        ];
        $company_role = Role::where('name','company')->first();
        foreach ($permissions as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','Taskly')->exists();
            if(!$table)
            {
                $table = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'Taskly',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($table);
                }
            }
        }
    }
}
