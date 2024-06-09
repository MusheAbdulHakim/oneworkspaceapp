<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            'Create User',
            'Customer Invoice Send',
            'Payment Reminder',
            'Invoice Payment Create',
            'Proposal Status Updated',
            'New Helpdesk Ticket',
            'New Helpdesk Ticket Reply',
            'Purchase Send',
            'Purchase Payment Create',

        ];
        $permissions = [
            'user manage',
            'invoice send',
            'invoice manage',
            'invoice payment create',
            'proposal send',
            'helpdesk manage',
            'helpdesk manage',
            'purchase send',
            'purchase payment create',
        ];
            foreach($notifications as $key=>$n){
                $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','general')->count();
                if($ntfy == 0){
                    $new = new Notification();
                    $new->action = $n;
                    $new->status = 'on';
                    $new->permissions = $permissions[$key];
                    $new->module = 'general';
                    $new->type = 'mail';
                    $new->save();
                }
            }
    }
}
