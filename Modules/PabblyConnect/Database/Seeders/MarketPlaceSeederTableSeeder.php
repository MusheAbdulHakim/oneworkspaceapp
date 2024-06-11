<?php

namespace Modules\PabblyConnect\Database\Seeders;

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

        $module = 'PabblyConnect';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'PabblyConnect';
        $data['product_main_description'] = '<p>Pabbly Connect is a workflow automation and integration platform that allows users to connect various apps and automate tasks between them. It is designed to simplify the process of data transfer and communication between different applications without the need for extensive coding or technical expertise.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Build Powerful <b>Automations</b> with No Code.</h2>';
        $data['dedicated_theme_description'] = '<p>Easy-to-use built-in tools that help you create workflows with advanced capabilities like scheduling, delay, router, and many more.</p>';
        $data['dedicated_theme_sections'] = '[
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Pabbly Connect integrates 1000+ apps, enabling instant data transfer.",
                "dedicated_theme_section_description": "",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": "Schedule Workflows",
                        "description": "Set up a date and time at which you want the workflow to be executed. Like on a specific day, once in a day, and every min/hour/day/week."
                    },
                    "2": {
                        "title": "Advance Workflows",
                        "description": "Create advanced workflows with the help of routers/paths. With the help of a router, you will be able to execute different actions based on the filter conditions you set."
                    },
                    "3": {
                        "title": "Delay Workflows",
                        "description": "Delay step allows you to delay performing the next action for a specified amount of period. The period can be either mins/hours/days/weeks or any specific date and time."
                    }
                }
            },
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Automate for enhanced workflow Efficiency.",
                "dedicated_theme_section_description": "",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": "Connect Any Application",
                        "description": "Integrate to any application with an API using API modules. This module offers custom options and supports many different API structures."
                    },
                    "2": {
                        "title": "Email Parser",
                        "description": "Email Parser automatically extracts data from the incoming emails like email name, subject, the body of the email, attachments, etc. which can be processed further."
                    },
                    "3": {
                        "title": "Iterator",
                        "description": "Iterator divides a collection of data into numerous values, which are then processed one by one until the last value is reached. Each value can be passed to other action steps added after the Iterator step."
                    }
                }
            },
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Imagine The Possibilities of Automating Anything!",
                "dedicated_theme_section_description": "<p>Pabbly Connect is a versatile integration platform that enables seamless connections between over 1000 applications. It facilitates the creation of automated workflows with triggers and actions, allowing users to streamline processes without the need for coding. The platform prioritizes real-time data transfer, ensuring swift synchronization between connected apps.</p>",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": null,
                        "description": null
                    }
                }
            }
        ]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"PabblyConnect"},{"screenshots":"","screenshots_heading":"PabblyConnect"},{"screenshots":"","screenshots_heading":"PabblyConnect"},{"screenshots":"","screenshots_heading":"PabblyConnect"},{"screenshots":"","screenshots_heading":"PabblyConnect"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modules for Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach ($data as $key => $value) {
            if (!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()) {
                MarketplacePageSetting::updateOrCreate(
                    [
                        'name' => $key,
                        'module' => $module
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }
    }
}