<?php

namespace Modules\PabblyConnect\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\LMS\Events\CreateBlog;
use Modules\PabblyConnect\Entities\PabblySend;

class CreateBlogLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateBlog $event)
    {
        if(module_is_active('PabblyConnect')){
            $request = $event->request;
            $blog = $event->blog;

            $action = 'New Blog';
            $module = 'LMS';
            $pabbly_array = array(
                "Blog Title"   => $blog['title'],
                "Blog Cover"   => $blog['blog_cover_image'],
                "Blog Details" => strip_tags($blog['detail']),
            );

            PabblySend::SendPabblyCall($module ,$pabbly_array,$action);
        }
    }
}