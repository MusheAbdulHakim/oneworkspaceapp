<?php

namespace Modules\Paypal\Database\Seeders;

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

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Paypal';
        $data['product_main_description'] = '<p>Check out faster, safer and more easily with PayPal, the service that lets you pay, send money, and accept payments without having to enter your financial details each time. PayPal prioritizes enabling secure transactions to allow users to send and receive money electronically. Compared to credit cards, PayPal can be considered as safe.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Paypal<b> Payment</b> Getway</h2>';
        $data['dedicated_theme_description'] = '<p>Compared to credit cards, PayPal can be considered as safe.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What are the benefits of using PayPal?","dedicated_theme_section_description":"<p>PayPal is a fast, secure way to pay online. We help you make purchases at millions of online stores in the U.S. and across more than 200 global markets – all without the hassle of converting currency. It’s free to sign up for an account and download our app to receive and send money. We also make it quick and simple to make automatic payments for all your monthly bills and subscriptions.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"The Advantages of Using PayPal","dedicated_theme_section_description":"<p>PayPal. It features a robust, widely accessible system. You can create a free PayPal account and start receiving payments within minutes. Of course, using PayPal now and then is one thing, but setting it as your default payment system is another. Assess the platform’s advantages and gauge how they’ll benefit your freelance business.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Paypal"},{"screenshots":"","screenshots_heading":"Paypal"},{"screenshots":"","screenshots_heading":"Paypal"},{"screenshots":"","screenshots_heading":"Paypal"},{"screenshots":"","screenshots_heading":"Paypal"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Paypal')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Paypal'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
