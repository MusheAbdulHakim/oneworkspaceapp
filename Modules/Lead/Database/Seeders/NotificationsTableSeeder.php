<?php

namespace Modules\Lead\Database\Seeders;

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

        $notifications = [
            'Deal Assigned','Deal Moved','New Task','Lead Assigned','Lead Moved','Lead Emails','Deal Emails'
        ];
        $permissions = [
            'deal manage',
            'deal move',
            'deal task create',
            'lead manage',
            'lead move',
            'lead email create',
            'deal email create',

        ];
        foreach($notifications as $key=>$n){
            $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Lead')->count();
            if($ntfy == 0){
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'Lead';
                $new->type = 'mail';
                $new->save();
            }
        }
    }
}
