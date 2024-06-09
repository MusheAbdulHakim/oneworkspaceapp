<?php

namespace Modules\Lead\Database\Seeders;

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
        $module = 'Lead';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'CRM';
        $data['product_main_description'] = '<p>Convert your leads to deals with ease. Easily manage multiple leads, track visibility and do a lot more with the ultimate lead management tool.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>What People <b>Are Saying</b> About CRM</h2>';
        $data['dedicated_theme_description'] = '<p>With Alligō, you can take care of the entire partner lifecycle - from onboarding through nurturing, cooperating, and rewarding. Find top performers and let go of those who aren’t a good fit.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Manage Your Leads Better. Convert Faster.","dedicated_theme_section_description":"<p>Skyrocket your sales with an effective lead management tool. Determine the value of leads and develop promising leads with ease. Get clearer action plans and make smarter and well-informed decisions.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Increase Your Reach","description":"Expand your business to every nook and cranny of the world. Communicate efficiently with your users in the language that they understand. Utilize a multilingual lead management tool that lets you generate payment in various currencies."},"2":{"title":"Cost-Efficient Lead Management","description":"Save money and manage your time effectively to improve your business productivity. Automate your lead management and start closing more deals and making more sales on autopilot."},"3":{"title":"Easy Customization","description":"Create custom design fields for your products, deals, and users. Easily manage and customize your dashboard to suit your needs. Make your invoices unique by accessing an efficient color palette and choosing from 10 attractive pdf templates."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Get A Complete View Of All Your Leads","dedicated_theme_section_description":"<p>A management tool to maintain precision in lead management. Using this proficient tool you can manage the visibility of your leads at different stages of the pipeline.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Manage All Your Leads Under One Roof","description":"Manage your clients, users, and deals from anywhere, and from a single tab. Access a wide range of features, get a graphical representation of your data, and make informed decisions."},"2":{"title":"Stay In Control","description":"Create and manage multiple users for various deals. Assign different roles to them and control their access levels. Upgrade permissions on the go and easily seal deals."},"3":{"title":"Get Tailored Reports","description":"Easily measure every aspect of your business from an intuitive interface. Generate insights that lead to more effective sales, and manage your leads and deals with a smooth drag-and-drop system."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Kanban Board for Deal Management","dedicated_theme_section_description":"<p>Manage deals with an easy drag-and-drop system. Easily relocate the deals to various stages through the Kanban board system.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"CRM is an all-in-one lead management tool.","dedicated_theme_section_description":"<p>Get an actionable analysis of all your leads, deals, users, invoices, and products - in one interactive dashboard. Create and implement effective business plans, set a maximum number of deals, payment options and do so much more.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"CRM"},{"screenshots":"","screenshots_heading":"CRM"},{"screenshots":"","screenshots_heading":"CRM"},{"screenshots":"","screenshots_heading":"CRM"},{"screenshots":"","screenshots_heading":"CRM"}]';
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

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
