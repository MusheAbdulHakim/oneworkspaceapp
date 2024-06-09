<?php

namespace Modules\Hrm\Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // email notification
        $notifications = [
            'Leave Status', 'New Award', 'Employee Complaints', 'New Payroll', 'Employee Promotion', 'Employee Resignation', 'Employee Termination', 'Employee Transfer', 'Employee Trip', 'Employee Warning', 'Employee Leave Received'
        ];
        $permissions = [
            'leave approver manage',
            'award manage',
            'complaint manage',
            'setsalary pay slip manage',
            'promotion manage',
            'resignation manage',
            'termination manage',
            'transfer manage',
            'travel manage',
            'warning manage',
            'leave manage'
        ];
        foreach ($notifications as $key => $n) {
            $ntfy = Notification::where('action', $n)->where('type', 'mail')->where('module', 'Hrm')->count();
            if ($ntfy == 0) {
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'Hrm';
                $new->type = 'mail';
                $new->save();
            }
        }
    }
}
