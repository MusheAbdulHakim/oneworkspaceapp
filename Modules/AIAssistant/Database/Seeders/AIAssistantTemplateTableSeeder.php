<?php

namespace Modules\AIAssistant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class AIAssistantTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this_module = Module::find('AIAssistant');
        $this_module->enable();
        $modules = Module::all();
        if(module_is_active('AIAssistant'))
        {
            foreach ($modules as $key => $value) {
                $name = '\Modules\\'.$value->getName();
                $path =   $value->getPath();
                if(file_exists($path.'/Database/Seeders/AIAssistantTemplateListTableSeeder.php'))
                {
                    $this->call($name.'\Database\Seeders\AIAssistantTemplateListTableSeeder');
                }
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
