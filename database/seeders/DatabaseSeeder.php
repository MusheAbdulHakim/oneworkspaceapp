<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(EmailTemplates::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(Plans::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(DefultSetting::class);
        $this->call(LanguageTableSeeder::class);
        if(module_is_active('AIAssistant'))
        {
            $this->call(AIAssistantTemplateListTableSeeder::class);
        }
    }
}
