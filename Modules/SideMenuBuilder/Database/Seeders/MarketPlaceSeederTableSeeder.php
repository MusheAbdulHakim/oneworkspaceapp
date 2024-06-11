<?php

namespace Modules\SideMenuBuilder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\MarketplacePageSetting;

class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Side Menu Builder';
        $data['product_main_description'] = '<p>Side menu builder are often used to provide a tailored and user-friendly experience by allowing users to access specific content or perform actions with a single click. Design a user-friendly form where users can input their Side Menu Builder details, such as the destination URL, link alias, and any additional parameters.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Get <b>Additional </b> Information for a Side Menu Builder</h2>';
        $data['dedicated_theme_description'] = '<p>Tailor and design user-friendly side menus for your applications with our Side Menu Builder</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Manage Side Menu Builder","dedicated_theme_section_description":"<p>You can create a module in the sidebar by simply clicking the Create button. In which you can manage the module and also edit that module according to your own. And you can also delete that module.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"what is the purpose of Side Menu Builder","dedicated_theme_section_description":"<p>Side Menu Builder allows you to guide users directly to particular web pages, content, or actions within a website or application. Instead of relying on standard or generic links, Side Menu Builder provides a more direct and relevant path to information or functionality.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Side Menu Builder"},{"screenshots":"","screenshots_heading":"Side Menu Builder"},{"screenshots":"","screenshots_heading":"Side Menu Builder"},{"screenshots":"","screenshots_heading":"Side Menu Builder"},{"screenshots":"","screenshots_heading":"Side Menu Builder"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach ($data as $key => $value) {
            if (!MarketplacePageSetting::where('name', $key)->where('module', 'SideMenuBuilder')->exists()) {
                MarketplacePageSetting::updateOrCreate(
                    [
                        'name' => $key,
                        'module' => 'SideMenuBuilder'
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }
    }
}
