<?php

namespace Modules\Pos\Database\Seeders;

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

        // email notification
        $notifications = [
            'Purchase Send','Purchase Payment Create',
        ];
        $permissions = [
            'purchase send',
            'purchase payment create'


        ];
            foreach($notifications as $key=>$n){
                $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Pos')->count();
                if($ntfy == 0){
                    $new = new Notification();
                    $new->action = $n;
                    $new->status = 'on';
                    $new->permissions = $permissions[$key];
                    $new->module = 'Pos';
                    $new->type = 'mail';
                    $new->save();
                }
            }

    }
}
