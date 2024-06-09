<?php

namespace Modules\Account\Database\Seeders;

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
        $data['product_main_heading'] = 'Accounting';
        $data['product_main_description'] = '<p>Account is an account management software that facilitates ease in revenue calculation by keeping a tab on all the accountancy matters of business. Based on Laravel, this accountancy software will make your business operations smooth and convenient. A graphical and tabular representation of various elements will help you make informed decisions for your firm.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'ACCOUNTING AND BILLING, SIMPLIFIED';
        $data['dedicated_theme_description'] = '<p>Accounting gives you the power to keep an eye on all accounting and inventory matters from one tab. You’ll never have to manage accounting with one tool, and control inventory with another - ever again!</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Account Helps You Simplify Your Accounting and Billing","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Simplify Your Accounting and Billing","description":"Simplify your accounting and make it easy to keep an eye on your money. Set financial goals and let the system monitor them for you, automate taxes, and more! - without lifting a finger."},"2":{"title":"Take Control Of Your Inventory","description":"Save time by managing your entire inventory with a few clicks. Easily create categories and add products to them. Modify product prices whenever you want, assign SKUs, create different tax rates, and do so much more!"},"3":{"title":"Take Your Project from Proposal to Payment","description":"Land new clients in a flash, and get paid just as fast. Create proposal templates and pitch your future clients. Turn your accepted proposals into payable invoices, send reminders, and get paid fast - all in one place!"}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Get All the Critical Tools to Manage Business Finances","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Manage Accounting Quickly and Easily","description":"Manage your billing and accounting without little to no effort! Set financial goals and let the system monitor them for you, automate taxes, and more! - without lifting a finger."},"2":{"title":"Handle Inventory Tasks Without Stress","description":"Easily manage inventory by creating categories and adding products to them. Modify product prices whenever you want, assign SKUs, create different tax rates, and do so much more!"},"3":{"title":"Take Your Project From Proposal To Payment","description":"Create proposal templates and pitch your future clients. Turn your accepted proposals into payable invoices, send reminders, and get paid fast - all in one place!"}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Reports","dedicated_theme_section_description":"<p>Get a report on transactions with an easy filtering option. Download the required account statements in either PDF, CSV, or Excel format. You get duly prepared reports on individual Income, Expenses, Tax, Invoice, and bill summary. Filter them based on Account, category, and customers. Also, a graphical display of the Income VS Expense chart along with a detailed calculation of Profit and Loss will help you make informed decisions. Filter the tax summary and Income VS Expense chart based on financial years.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Budget Planner","dedicated_theme_section_description":"<p>A budget is a financial plan for a specified period to keep in check with the working capital. This feature here helps to maintain the capital flow. You can set monthly, quarterly, half-yearly, or yearly budgets according to your business plans and needs. The main categories are “Income” and “Expense” where one can edit /update /delete the sub-categories as well.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Accounting"},{"screenshots":"","screenshots_heading":"Accounting"},{"screenshots":"","screenshots_heading":"Accounting"},{"screenshots":"","screenshots_heading":"Accounting"},{"screenshots":"","screenshots_heading":"Accounting"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Account')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Account'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
