<?php

namespace Modules\Stripe\Database\Seeders;

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
        $data['product_main_heading'] = 'Stripe';
        $data['product_main_description'] = '<p>Stripe is an online payment processing and credit card processing platform for businesses. When a customer buys a product online, the funds need to be delivered to the seller Insert Stripe. Stripe allows safe and efficient processing of funds via credit card or bank and transfers those funds to the sellers account.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Stripe Payment Gateway';
        $data['dedicated_theme_description'] = '<p>Stripe is an online payment processing and credit card processing platform for businesses.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Why use Stripe payment?","dedicated_theme_section_description":"<p>Stripe Payments is a powerful payment processor for online sales. It can accept dozens of payment methods and more than 135 currencies. And its advanced developer tools allow you to create a checkout flow that feels custom, provided you know how to use them.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Stripe payment gateway","dedicated_theme_section_description":"<p>Stripe users can accept in-person payments by integrating the company’s point-of-sale option, Stripe Terminal. But if you do most of your business in person, there are better options. For example, other payment processors, such as Square, offer specific restaurant and retail features like end-of-day reports and inventory tracking.That said, Stripe’s flat-rate pricing is easy to understand and there are no monthly fees. In addition, you can cancel at any time if it isn’t a good fit.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Stripe"},{"screenshots":"","screenshots_heading":"Stripe"},{"screenshots":"","screenshots_heading":"Stripe"},{"screenshots":"","screenshots_heading":"Stripe"},{"screenshots":"","screenshots_heading":"Stripe"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Stripe')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Stripe'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
