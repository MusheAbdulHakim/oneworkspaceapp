<?php

namespace Modules\AIAssistant\Database\Seeders;

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
        $data['product_main_heading'] = 'AI Assistant';
        $data['product_main_description'] = '<p>Generating text, writing a variety of creative content and answering your questions in an informative manner. Quickly and accurately summarize long articles or research papers. Generating various creative text formats of text content like poems, emails, letters etc.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'AIAssistant';
        $data['dedicated_theme_description'] = '<p>AIAssistant can be used to generate content that is both high-quality and engaging. It can also be used to personalize content for specific target audiences.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Some Benefits of Using AIAssistant","dedicated_theme_section_description":"<p>AIAssistant can generate high-quality content that is both informative and engaging. AIAssistant can personalize your content for specific target audiences, ensuring that your message resonates with your readers. AIAssistant can generate content that is optimized for search engines, helping you to drive more traffic to your website.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":"null","description":null},"3":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What is use of AIAssistant?","dedicated_theme_section_description":" <p>AIAssistant can automate tasks such as scheduling appointments, managing finances, and providing reminders. This can save you time and effort, and help you to be more productive.  AIAssistant is being used to create content for a variety of industries, including marketing, advertising, and education. For example, AIAssistant can be used to generate blog posts, social media captions.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"AIAssistant"},{"screenshots":"","screenshots_heading":"AIAssistant"},{"screenshots":"","screenshots_heading":"AIAssistant"},{"screenshots":"","screenshots_heading":"AIAssistant"},{"screenshots":"","screenshots_heading":"AIAssistant"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'AIAssistant')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'AIAssistant'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
