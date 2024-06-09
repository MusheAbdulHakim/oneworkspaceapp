<?php

namespace App\Classes;

class Setting
{
    public $html = [];
    public $settings;
    public $user;
    public $modules;

    public function __construct($user,$settings)
    {
        $this->user = $user;
        $this->settings = $settings;
        $this->modules = ActivatedModule();
        $this->modules[] =  'Base';
    }

    public function add(array $array): void {
        if(in_array($array['module'],$this->modules) && ((empty($array['permission'])) ||  $this->user->isAbleTo($array['permission']))){
            $this->html[] = $array;
        }
    }

    public function getSettings(){
        return $this->settings;
    }
}
