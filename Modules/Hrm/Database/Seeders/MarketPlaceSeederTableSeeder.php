<?php

namespace Modules\Hrm\Database\Seeders;

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
        $data['product_main_heading'] = 'HRM';
        $data['product_main_description'] = '<p>Your employees are your business’s greatest assets. Whether you employ 5, 50, or 500 people, with HRMGo, you can manage all employee matters. From hiring to performance and salaries - everything under one roof. Customizable, versatile, and easy-to-use complete HRM system.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'The Complete HRM System Your Business Needs…';
        $data['dedicated_theme_description'] = '<p>With HRMGo, you can manage key employee matters, recruit new candidates, boost employee productivity, and pay your employees for their hard work - Without stress, all from one place. Whether youre a consultant, small business owner, or large corporation - the system is designed for everyone.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Manage Your HR Like a Boss","dedicated_theme_section_description":"<p>This comprehensive feature facilitates the activities of HR. It is easy to maintain a record of promotions, transfers, work trips, terminations, warnings, and other important aspects of HR.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Manage Key Employee Matters Easily","description":"Create a profile for every employee and track their key information, including position, salary, and career progress. Update and change their information in just a few clicks. Track employee contract status. Transfer them to different departments, branches, or terminate the contract if needed."},"2":{"title":"Recruit New Candidates and Grow Your Team","description":"Speed up your hiring process. Use built-in hiring features to create and manage new job openings and fill your open positions faster. Collect and manage applications from start to finish. Easily compare candidates and pick the best one for the job."},"3":{"title":"Pay Your Employees for their Hard Work","description":"Manage payroll in just a few clicks. Calculate salaries, schedule deposits, and make sure your employees get paid on time. Keep data of all workforce costs, transfers, deposits, and other employee-related transactions for future reference."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Everything You Need For a Successful HRM - In One Place","dedicated_theme_section_description":"<p>This feature makes it easier for a company to maintain a record of an employee’s personal, company, and Bank details along with their essential documentation. Employees could view and manage their profiles.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Say Goodbye To Spending Thousands Of Dollars On Hiring HR Managers","description":"Need to fill an open position? Just create a new job opening in HRM and manage the process from start to finish. With HRM, recruit new candidates faster and save time on hiring managers. Create a job opening, collect and manage applications, create a candidate pipeline, and handle interviews."},"2":{"title":"Help Your Employees Become More Productive","description":"Empower employee growth. Schedule skills training, track expenses, and watch your employees become better at their work. Boost employee productivity with custom KPIs. Track employee performance, share feedback, and help them reach company targets."},"3":{"title":"Manage Payroll in Just a Few Clicks","description":"Pay your employees for their hard work. Keep data of all workforce costs, transfers, deposits, and other employee-related transactions for future reference. Track employee attendance and overtime to ensure they always receive fair compensation for their work."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Manage Payroll Effortlessly","dedicated_theme_section_description":"<p>You can generate monthly payslips and make bulk payments through easy clicks. You could also change the status of the payslip with an easy CTA. An employee could view the breakdown of their salary components.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Recruit New Candidates and Grow Your Team","dedicated_theme_section_description":"<p>Use built-in hiring features to create and manage new job openings and fill your open positions faster. Schedule interviews, create interview questions and assign interviewers in just a few clicks.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"HRM"},{"screenshots":"","screenshots_heading":"HRM"},{"screenshots":"","screenshots_heading":"HRM"},{"screenshots":"","screenshots_heading":"HRM"},{"screenshots":"","screenshots_heading":"HRM"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Hrm')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Hrm'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
