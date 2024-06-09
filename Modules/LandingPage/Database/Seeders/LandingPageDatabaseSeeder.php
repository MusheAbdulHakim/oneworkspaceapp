<?php

namespace Modules\LandingPage\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;
use Modules\LandingPage\Entities\LandingPageSetting;


class LandingPageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(LandingPageDataTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this_module = Module::find('LandingPage');
        $this_module->enable();
        $modules = Module::all();
        if(module_is_active('LandingPage'))
        {
            foreach ($modules as $key => $value) {
                $name = '\Modules\\'.$value->getName();
                $path =   $value->getPath();
                if(file_exists($path.'/Database/Seeders/MarketPlaceSeederTableSeeder.php'))
                {
                    $this->call($name.'\Database\Seeders\MarketPlaceSeederTableSeeder');
                }
            }
        }
    }
}
