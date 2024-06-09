<?php

namespace Modules\Account\Database\Seeders;

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
            'Bill Send','Bill Payment Create','Revenue Payment Create'
        ];
        $permissions = [
            'bill send',
            'bill payment create',
            'revenue create'

        ];
            foreach($notifications as $key=>$n){
                $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Account')->count();
                if($ntfy == 0){
                    $new = new Notification();
                    $new->action = $n;
                    $new->status = 'on';
                    $new->permissions = $permissions[$key];
                    $new->module = 'Account';
                    $new->type = 'mail';
                    $new->save();
                }
            }

    }
}
