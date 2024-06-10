<?php

namespace Modules\GHL\Traits;

trait UserGHL
{
    public function  userGHL(){
        $ghlAccess = auth()->user()->ghl;
        if(!empty($ghlAccess)){
            return $ghlAccess = auth()->user()->ghl;
        }
        return null;
    }

    public function initGHL()
    {
        $access = $this->userGHL();
        if(!empty($access)){
            return \MusheAbdulHakim\GoHighLevel\GoHighLevel::init($access->access_token);
        }
        return null;
    }
}
