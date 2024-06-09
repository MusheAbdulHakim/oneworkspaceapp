<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:forget spatie.permission.cache');
        Artisan::call('cache:clear');

        // Super Admin
        $admin = User::where('type','super admin')->first();
        if(empty($admin))
        {
            $admin = new User();
            $admin->name = 'Super Admin';
            $admin->email = 'superadmin@example.com';
            $admin->password = Hash::make('1234');
            $admin->email_verified_at = date('Y-m-d H:i:s');
            $admin->type = 'super admin';
            $admin->active_status = 1;
            $admin->active_workspace = 0;
            $admin->avatar = 'uploads/users-avatar/avatar.png';
            $admin->dark_mode = 0;
            $admin->lang = 'en';
            $admin->workspace_id = 0;
            $admin->created_by = 0;
            $admin->save();

            $role = Role::where('name','super admin')->where('guard_name','web')->exists();
            if(!$role)
            {
                $superAdminRole        = Role::create(
                    [
                        'name' => 'super admin',
                        'created_by' => 0,
                    ]
                );
            }
            $role_r = Role::where('name','super admin')->first();
            $admin->addRole($role_r);
        }

        $adnin_permission = [
            'user manage',
            'user create',
            'user edit',
            'user delete',
            'user profile manage',
            'user reset password',
            'user login manage',
            'user import',
            'user logs history',
            'setting manage',
            'setting storage manage',
            'coupon manage',
            'coupon create',
            'coupon edit',
            'coupon delete',
            'plan manage',
            'plan create',
            'plan edit',
            'plan delete',
            'plan orders',
            'module manage',
            'module add',
            'module remove',
            'module edit',
            'email template manage',
            'language manage',
            'language create',
            'language delete',
            'helpdesk manage',
            'helpdesk ticket manage',
            'helpdesk ticket create',
            'helpdesk ticket edit',
            'helpdesk ticket show',
            'helpdesk ticket reply',
            'helpdesk ticket delete',
            'helpdeskticket setup manage',
            'helpdesk ticketcategory manage',
            'helpdesk ticketcategory create',
            'helpdesk ticketcategory edit',
            'helpdesk ticketcategory delete',

            'api key setting manage',
            'api key setting create',
            'api key setting edit',
            'api key setting delete',

            'notification template manage',

            'referral program manage',
        ];

            $compnay_permission = [
                'user manage',
                'user create',
                'user edit',
                'user delete',
                'user profile manage',
                'user chat manage',
                'user reset password',
                'user login manage',
                'user import',
                'user logs history',
                'workspace manage',
                'workspace create',
                'workspace edit',
                'workspace delete',
                'roles manage',
                'roles create',
                'roles edit',
                'roles delete',
                'plan manage',
                'plan purchase',
                'plan subscribe',
                'plan orders',
                'proposal manage',
                'proposal create',
                'proposal edit',
                'proposal delete',
                'proposal show',
                'proposal send',
                'proposal duplicate',
                'proposal product delete',
                'proposal convert invoice',
                'invoice manage',
                'invoice create',
                'invoice edit',
                'invoice delete',
                'invoice show',
                'invoice send',
                'invoice duplicate',
                'invoice product delete',
                'invoice payment create',
                'invoice payment delete',
                'setting manage',
                'helpdesk ticket manage',
                'helpdesk ticket create',
                'helpdesk ticket edit',
                'helpdesk ticket show',
                'helpdesk ticket reply',
                'helpdesk ticket delete',
                'purchase manage',
                'purchase create',
                'purchase edit',
                'purchase delete',
                'purchase show',
                'purchase send',
                'purchase payment create',
                'purchase payment delete',
                'purchase product delete',
                'purchase debitnote create',
                'purchase debitnote edit',
                'purchase debitnote delete',
                'report warehouse',
                'report purchase',
                'warehouse manage',
                'warehouse create',
                'warehouse edit',
                'warehouse delete',
                'warehouse show',
                'warehouse import',
                'referral program manage',
            ];


        $superAdminRole  = Role::where('name','super admin')->first();
        foreach ($adnin_permission  as $key => $value)
        {
            $permission = Permission::where('name',$value)->first();
            if(empty($permission))
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'General',
                        'created_by' => $admin->id,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
            if(!$superAdminRole->hasPermission($value))
            {
                $superAdminRole->givePermission($permission);
            }
        }
        // Company ..
        $role = Role::where('name','company')->where('guard_name','web')->exists();
        if(!$role)
        {
            $company_role        = Role::create(
                [
                    'name' => 'company',
                    'created_by' => $admin->id,
                ]
            );
        }
        $company_role = Role::where('name','company')->first();
        foreach ($compnay_permission as $key => $value)
        {
            $permission = Permission::where('name',$value)->first();
            if(empty($permission))
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'General',
                        'created_by' => $admin->id,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
            if(!$company_role->hasPermission($value))
            {
                $company_role->givePermission($permission);
            }
        }


        $company = User::where('type','company')->first();
        try{

            $assigned_role = $company->roles->first();
        }catch(\Exception $e){
            $assigned_role = null;
        }
        if(!$assigned_role && !empty($company))
        {
            $company->addRole($company_role);
        }
    }
}
