<?php

namespace Modules\LandingPage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Utility;


class LandingPageSetting extends Model
{
    public static $settings_data;
    use HasFactory;

    protected $table = 'landing_page_settings';

    protected $fillable = [
        'name',
        'value'
    ];

    public static $fontweight = [
        'normal' => 'Normal',
        'bold' => 'Bold',
        'lighter' => 'Lighter',
        'bolder' => 'Bolder',
    ];

    protected static function newFactory()
    {
        return \Modules\LandingPage\Database\factories\LandingPageSettingFactory::new();
    }

    public static function settings()
    {
        if(self::$settings_data == null)
        {
            $data = LandingPageSetting::get();
            self::$settings_data = $data;
        }

        $settings = [
            'topbar_status' =>'on',
            'topbar_notification_msg' =>'70% Special Offer. Don’t Miss it. The offer ends in 72 hours.',
            'menubar_status' =>'on',
            'menubar_page' =>'[{"template_name":"page_content","page_url":"","menubar_page_contant":"<p>At WorkDo our vision is to become a one-stop destination for all your IT needs by creating disruptive web solutions that remain accessible to all. We diligently work towards bringing our clients IT solutions that transform the way their businesses function. Rather than confuse you with the complexities of web services we focus on bringing our clients simplified web solutions. From Web development, to Web maintenance, we are dedicated to making your IT life easier.<\/p>","login":"on","menubar_page_name":"About Us","menubar_page_short_description":"WorkDo offers comprehensive web solutions to businesses. We aim to provide products that are beautifully designed, user friendly and a delight to use.","page_slug":"about_us","header":"on","footer":"on"}]',
            'site_logo' =>'site_logo.png',
            'site_description' =>'',
            'home_status' =>'on',
            'home_offer_text' =>'70% Special Offer1',
            'home_title' =>'Home',
            'home_heading' =>'With Dash, you can take care of each need of your business functions in.one place',
            'home_description' =>'Lay a solid foundation for your brand. Grab a high-converting web powered by a secure backend coupled with an intuitive eCommerce.',
            'home_trusted_by' =>'Our best partners and +11,000 customers worldwide satisfied with our services.',
            'home_live_demo_link' =>'https://dash-demo.workdo.io/login',
            'home_buy_now_link' =>'',
            'home_banner' =>'home_banner.png',
            'home_logo' =>'home_logo.png',
            'home_link_button_text' =>'View Live Demo',
            'feature_status' =>'on',
            'feature_title' =>'Features',
            'feature_heading' =>'',
            'feature_description' =>'',
            'feature_buy_now_link' =>'',
            'feature_more_details_link' =>'',
            'feature_of_features' =>'[{"feature_logo":"1690960676-feature_logo.png","feature_heading":"Free & Open Source","feature_description":"<p>Give your customers a quick way to find the products they want. Thanks to a built-in, intuitive search and filtering, they can view only those products that they&rsquo;re interested in.<\/p>","feature_more_details_link":"#","feature_more_details_button_text":"Find Out More"},{"feature_logo":"1690960805-feature_logo.png","feature_heading":"Guaranteed to Grow Your Business","feature_description":"<p>Give your customers a quick way to find the products they want. Thanks to a built-in, intuitive search and filtering, they can view only those products that they\u2019re interested in.<\/p>","feature_more_details_link":"#","feature_more_details_button_text":"Find Out More"},{"feature_logo":"1690960837-feature_logo.png","feature_heading":"24\/7 Support","feature_description":"<p>Give your customers a quick way to find the products they want. Thanks to a built-in, intuitive search and filtering, they can view only those products that they&rsquo;re interested in.<\/p>","feature_more_details_link":"#","feature_more_details_button_text":"Find Out More"}]',
            'feature_logo' =>'',
            'highlight_feature_heading' =>'Why choose dedicated modules for Your Business?',
            'highlight_feature_description' =>'With Dash, you can conveniently manage all your business functions from a single location.',
            'highlight_feature_image' =>'',
            'other_features' =>'[{"other_features_image":"1690961114-other_features_image.png","other_features_tag":"SALES","other_features_heading":"Account Helps You Simplify Your Accounting and Billing","other_featured_description":"Manage your billing and accounting without little to no effort! Set financial goals and let the system monitor them for you, automate taxes, and more! - without lifting a finger.","other_feature_buy_now_link":null,"cards":{"1":{"title":"Simplify Your Accounting and Billing","description":"Simplify your accounting and make it easy to keep an eye on your money. Set financial goals and let the system monitor them for you, automate taxes, and more! - without lifting a finger."},"2":{"title":"Take Control Of Your Inventory","description":"Save time by managing your entire inventory with a few clicks. Easily create categories and add products to them. Modify product prices whenever you want, assign SKUs, create different tax rates, and do so much more!"},"3":{"title":"Take Your Project from Proposal to Payment","description":"Land new clients in a flash, and get paid just as fast. Create proposal templates and pitch your future clients. Turn your accepted proposals into payable invoices, send reminders, and get paid fast - all in one place!"}}},{"other_features_image":"1690961251-other_features_image.png","other_features_tag":"SALES","other_features_heading":"Everything You Need For a Successful HRM - In One Place","other_featured_description":"<p>This feature makes it easier for a company to maintain a record of an employee&rsquo;s personal, company, and Bank details along with their essential documentation. Employees could view and manage their profiles.<\/p>","other_feature_buy_now_link":null,"cards":{"1":{"title":"Manage Key Employee Matters Easily","description":"Create a profile for every employee, track their key information, and update the information in just a few clicks. Collect and analyze feedback about their work, including warnings and complaints issued by their managers or other employees."},"2":{"title":"Help Your Employees Become More Productive","description":"Empower employee growth. Schedule skills training, track expenses, and watch your employees become better at their work. Boost employee productivity with custom KPIs. Track employee performance, share feedback, and help them reach company targets."},"3":{"title":"Manage Payroll in Just a Few Clicks","description":"Pay your employees for their hard work. Keep data of all workforce costs, transfers, deposits, and other employee-related transactions for future reference. Track employee attendance and overtime to ensure they always receive fair compensation for their work."}}},{"other_features_image":"1690961340-other_features_image.png","other_features_tag":"SALES","other_features_heading":"Easily manage all your projects and keep your business growing.","other_featured_description":"<p>Got a big team or working on multiple projects at once? Manage task priorities or even create additional workspaces and use the built-in permission system to separate core projects. Make your team more effective by helping them avoid confusion ensuring they always know what to focus on.<\/p>","other_feature_buy_now_link":null,"cards":{"1":{"title":"Kanban Task Management","description":"Whether you need a simple tool to track your tasks, are a Kanban fan, want to create Gantt Charts or are looking for a convenient tool to track your projects - Taskly got you covered."},"2":{"title":"Creating milestones and assigning subtasks","description":"Add a new task to an already existing project and prioritize them according to the need of urgency. Assign the task to team members and set a due date for task completion. Add comments to the task and create a sub-task for ease of completion. Attach necessary files in a required task."},"3":{"title":"Bugs Resolution","description":"Create new bugs and assign users and priority to them. You can write a note in the text box for the bug description. Also, the status of each bug could be changed through an easy drop-down and Kanban drag system."}}},{"other_features_image":"1690961430-other_features_image.png","other_features_tag":"SALES","other_features_heading":"Manage Your Leads Better. Convert Faster.","other_featured_description":"<p>Skyrocket your sales with an effective lead management tool. Determine the value of leads and develop promising leads with ease. Get clearer action plans and make smarter and well-informed decisions.<\/p>","other_feature_buy_now_link":null,"cards":{"1":{"title":"Manage All Your Leads Under One Roof","description":"Manage your clients, users, and deals from anywhere, and from a single tab. Access a wide range of features, get a graphical representation of your data, and make informed decisions."},"2":{"title":"Cost-Efficient Lead Management","description":"Save money and manage your time effectively to improve your business productivity. Automate your lead management and start closing more deals and making more sales on autopilot."},"3":{"title":"Get Tailored Reports","description":"Easily measure every aspect of your business from an intuitive interface. Generate insights that lead to more effective sales, and manage your leads and deals with a smooth drag-and-drop system."}}},{"other_features_image":"1690961507-other_features_image.png","other_features_tag":"SALES","other_features_heading":"Ease in maintaining customer and vendor details","other_featured_description":"<p>POS allows you to create and maintain the data of each customer and vendor. You get access to all essential information through a well-maintained format.<\/p>","other_feature_buy_now_link":null,"cards":{"1":{"title":"Manage vital information from one dashboard","description":"Stay on top of your total and monthly purchases and sales. Get an interactive purchase and sales report graph to help you make informed decisions. Get progress reports of each branch, along with to-do lists and event calendars."},"2":{"title":"Set your sales targets and achieve them faster","description":"Create sales targets and keep track of their progress in your dashboard. Use the expense list to cut down on unnecessary expenses, and put more resources into reaching your sales targets."},"3":{"title":"Manage your products with ease","description":"Never stress about managing your inventory ever again! With PosGo, you can create your products and assign them a brand, category, unit and tax rate. You can even modify product descriptions, images, and price whenever you want."}}}]',
            'screenshots_status' =>'on',
            'screenshots_heading' =>'',
            'screenshots_description' =>'',
            'screenshots' =>'[{"screenshots":"1690966361-screenshots.png","screenshots_heading":"Project"},{"screenshots":"1690966398-screenshots.png","screenshots_heading":"POS"},{"screenshots":"1690966421-screenshots.png","screenshots_heading":"CRM"},{"screenshots":"1690966443-screenshots.png","screenshots_heading":"Accounting"},{"screenshots":"1690966482-screenshots.png","screenshots_heading":"HRM"}]',
            'footer_status' =>'on',
            'joinus_status' =>'on',
            'joinus_heading' =>'Join Our Community',
            'joinus_description' =>'We build modern web tools to help you jump-start your daily business work.',
            'footer_logo' =>'1690967309-footer_logo.png',
            'footer_description' =>'We build modern web tools to help you jump-start your daily business work.',
            'footer_live_demo_link' =>'#',
            'all_rights_reserve_text' =>'All Rights Reserved to',
            'footer_support_link' =>'#',
            'all_rights_reserve_website_name' =>'workdo.io',
            'all_rights_reserve_website_url' =>'https://www.workdo.io',
            'footer_sections_details' =>'[{"footer_section_heading":"Company","footer_section_text":{"1":{"title":"About Us","link":"#"},"2":{"title":"Freebies","link":"#"},"3":{"title":"Premium","link":"#"},"4":{"title":"Blog","link":"#"},"5":{"title":"Affiliate Program","link":"#"},"6":{"title":"Get coupon","link":"#"}}},{"footer_section_heading":"Help and Support","footer_section_text":{"1":{"title":"Knowledge Center","link":"#"},"2":{"title":"Contact Us","link":"#"},"3":{"title":"Premium Support","link":"#"},"4":{"title":"Sponsorships","link":"#"},"5":{"title":"Custom Development","link":"#"}}},{"footer_section_heading":"Help and Support","footer_section_text":{"1":{"title":"Terms & Conditions","link":"#"},"2":{"title":"Privacy Policy","link":"#"},"3":{"title":"Licenses","link":"#"}}}]',
            'footer_gotoshop_button_text' =>'Go to Shop',
            'footer_support_button_text' =>'Support',
            'reviews' =>'[{"review_header_tag":"SOLID FOUNDATION","review_heading":"A style theme, together with a dedicated Laravel backend and an intuitive mobile app","review_description":"<p>gives your business an unfair advantage. The package doesn\u2019t just provide you with everything you need to start selling online. It gives you a solid foundation for an eCommerce business for years to come.<\/p>","review_live_demo_link":"https:\/\/dash-demo.workdo.io\/dash\/login","review_live_demo_button_text":"View Live Demo"},{"review_header_tag":"SOLID FOUNDATION","review_heading":"A style theme, together with a dedicated Laravel backend and an intuitive mobile app","review_description":"<p>gives your business an unfair advantage. The package doesn\u2019t just provide you with everything you need to start selling online. It gives you a solid foundation for an eCommerce business for years to come.<\/p>","review_live_demo_link":"https:\/\/dash-demo.workdo.io\/dash\/login","review_live_demo_button_text":"View Live Demo"},{"review_header_tag":"SOLID FOUNDATION","review_heading":"A style theme, together with a dedicated Laravel backend and an intuitive mobile app","review_description":"<p>gives your business an unfair advantage. The package doesn\u2019t just provide you with everything you need to start selling online. It gives you a solid foundation for an eCommerce business for years to come.<\/p>","review_live_demo_link":"https:\/\/dash-demo.workdo.io\/dash\/login","review_live_demo_button_text":"View Live Demo"},{"review_header_tag":"SOLID FOUNDATION","review_heading":"A style theme, together with a dedicated Laravel backend and an intuitive mobile app","review_description":"<p>gives your business an unfair advantage. The package doesn\u2019t just provide you with everything you need to start selling online. It gives you a solid foundation for an eCommerce business for years to come.<\/p>","review_live_demo_link":"https:\/\/dash-demo.workdo.io\/dash\/login","review_live_demo_button_text":"View Live Demo"},{"review_header_tag":"SOLID FOUNDATION","review_heading":"A style theme, together with a dedicated Laravel backend and an intuitive mobile app","review_description":"gives your business an unfair advantage. The package doesn\u2019t just provide you with everything you need to start selling online. It gives you a solid foundation for an eCommerce business for years to come.","review_live_demo_link":"https:\/\/dash-demo.workdo.io\/dash\/login","review_live_demo_button_text":"View Live Demo"}]',
            'dedicated_heading' =>'Why Choose a Dedicated Fashion Theme for Your Business?',
            'dedicated_description' =>'With Alligō, you can take care of the entire partner lifecycle - from onboarding through nurturing, cooperating, and rewarding. Find top performers and let go of those who arent a good fit.',
            'dedicated_live_demo_link' =>'https://dash-demo.workdo.io/login',
            'dedicated_link_button_text' =>'View Live Demo',
            'dedicated_card_details' =>'[{"dedicated_card_logo":"1690966583-dedicated_card_logo.png","dedicated_card_heading":"High-Performing, Secure PHP Framework","dedicated_card_description":"Unlike many frameworks that come and go, the framework stood the test of time. Over the years, it grew to become one of the fastest and most secure frameworks in the market.","dedicated_card_more_details_link":"#","dedicated_card_more_details_button_text":"Find Out More"},{"dedicated_card_logo":"1690966606-dedicated_card_logo.png","dedicated_card_heading":"Stable Codebase","dedicated_card_description":"Some frameworks come and go - but Laravel is here to stay. Laravels active developer community helps keep its codebase up-to-date and stable. This, in turn, helps ensure the stability of your eCommerce website.","dedicated_card_more_details_link":"#","dedicated_card_more_details_button_text":"Find Out more"},{"dedicated_card_logo":"1690966638-dedicated_card_logo.png","dedicated_card_heading":"Secure Integrations","dedicated_card_description":"As you grow, you may want to expand your store with new functionalities or payment methods. Thanks to Laravels flexibility, it\u2019s easy to add new integrations and customize the store even once its already developed.<","dedicated_card_more_details_link":"#","dedicated_card_more_details_button_text":"Find Out more"}]',
            'dedicated_section_status' =>'on',
            'buildtech_heading' =>'Built with Technology You Can Trust',
            'buildtech_description' =>'Our backend is built with Laravel - one of the most popular and highest-rated web development frameworks. Find out why we chose it - and how it benefits your business.',
            'buildtech_live_demo_link' =>'',
            'buildtech_link_button_text' =>'',
            'buildtech_card_details' =>'[{"buildtech_card_logo":"1690966770-buildtech_card_logo.png","buildtech_card_heading":"Sell More Than Your Competitors","buildtech_card_description":"Your online store has one goal - to sell your products. Thanks to years of experience in the industry, we know the ins and outs of online sales. And we put that knowledge into every package that we offer. With the Style eCommerce package, you get a store that\u2019s optimized for helping you sell more in the fashion niche.","buildtech_card_more_details_link":"#","buildtech_card_more_details_button_text":"Find Out More"},{"buildtech_card_logo":"1690966899-buildtech_card_logo.png","buildtech_card_heading":"Get a Headstart over Your Competitors","buildtech_card_description":"In business, you have to act fast. By choosing our Style theme package, you can get everything you need to start selling right away. Hit the market with your product sooner, attract early sales, and build an audience from day one.","buildtech_card_more_details_link":"#","buildtech_card_more_details_button_text":"Find Out More"},{"buildtech_card_logo":"1690966833-buildtech_card_logo.png","buildtech_card_heading":"Avoid Design Mistakes","buildtech_card_description":"When you get a ready-made package, you avoid common design mistakes that could cost your business a fortune. Not only that. Thanks to a higher conversion rate, you can achieve better ROI on your marketing expenses.","buildtech_card_more_details_link":"#","buildtech_card_more_details_button_text":"Find Out More"},{"buildtech_card_logo":"1690966858-buildtech_card_logo.png","buildtech_card_heading":"Build a Long-Term Asset","buildtech_card_description":"The key to success in eCommerce is to scale your store and build an audience of loyal, recurring customers. With our package, you get more than just a store. You get an asset that\u2019s ready for you to take care of it and grow it for years to come.","buildtech_card_more_details_link":"#","buildtech_card_more_details_button_text":"Find Out More"}]',
            'buildtech_section_status' =>'on',
            'packagedetails_section_status' =>'on',
            'packagedetails_heading' =>'Start an Online Fashion Business with a Complete eCommerce Package',
            'packagedetails_short_description' =>'Get a fashion-themed eCommerce store with a secure backend and convenient mobile app. Build a brand, manage your store wherever you are, and grow an online business.',
            'packagedetails_long_description' =>'An effective fashion theme should be visually appealing and easy to navigate. A good theme makes it easy for customers to find and buy the items they&rsquo;re interested in. The theme should also be responsive so that it looks good on all devices.With the Style theme, you get all of the above - and more. The theme gives you everything you need to sell your products and keep your audience coming back for more. Easily customize the theme and adjust its design to your branding needs. Add products, polish product pages, and start growing your online business.',
            'packagedetails_link' =>'https://dash-demo.workdo.io/pricing',
            'packagedetails_button_text' =>'Get the Package',
            'discover_status' =>'on',
            'discover_heading' =>'',
            'discover_description' =>'',
            'discover_live_demo_link' =>'',
            'discover_buy_now_link' =>'',
            'discover_of_features' =>'',
            'plan_status' =>'on',
            'plan_title' =>'Plan',
            'plan_heading' =>'',
            'plan_description' =>'',
            'faq_status' =>'on',
            'faq_title' =>'Faq',
            'faq_heading' =>'',
            'faq_description' =>'',
            'faqs' =>'',
            'testimonials_status' =>'on',
            'testimonials_heading' =>'',
            'testimonials_description' =>'',
            'testimonials_long_description' =>'',
            'testimonials' =>'',
            'feature_of_features_cards' =>'',
            'landing_page_section_sequence' => '["is_banner_section_active","is_features_section_active","is_screenshots_section_active","is_buildtech_section_active","is_reviews_section_active","is_package_details_section_active","is_dedicated_section_active"]',
            'is_top_bar_active' => 'on',
            'is_banner_section_active' => 'on',
            'is_features_section_active' => 'on',
            'is_reviews_section_active' => 'on',
            'is_screenshots_section_active' => 'on',
            'is_dedicated_section_active' => 'on',
            'is_faq_section_active' => 'on',
            'is_buildtech_section_active' => 'on',
            'is_package_details_section_active' => 'on',

        ];

        foreach(self::$settings_data as $row)
        {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function keyWiseUpload_file($request, $key_name, $name, $path, $data_key, $custom_validation = [])
	{
		$multifile = [
		    $key_name => $request->file($key_name)[$data_key][$key_name],
		];

		try {
		    $settings = getAdminAllSetting();

		    if (!empty($settings['storage_setting'])) {

		        if ($settings['storage_setting'] == 'wasabi') {

		            config(
		                [
		                    'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
		                    'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
		                    'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
		                    'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
		                    'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
		                ]
		            );

		            $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
		            $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
		        } else if ($settings['storage_setting'] == 's3') {
		            config(
		                [
		                    'filesystems.disks.s3.key' => $settings['s3_key'],
		                    'filesystems.disks.s3.secret' => $settings['s3_secret'],
		                    'filesystems.disks.s3.region' => $settings['s3_region'],
		                    'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
		                    'filesystems.disks.s3.use_path_style_endpoint' => false,
		                ]
		            );
		            $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
		            $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
		        } else {
		            $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

		            $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
		        }


		        $file = $request->$key_name;


		        if (count($custom_validation) > 0) {
		            $validation = $custom_validation;
		        } else {

		            $validation = [
		                'mimes:' . $mimes,
		                'max:' . $max_size,
		            ];
		        }

		        $validator = \Validator::make($multifile, [
		            $key_name => $validation
		        ]);


		        if ($validator->fails()) {
		            $res = [
		                'flag' => 0,
		                'msg' => $validator->messages()->first(),
		            ];
		            return $res;
		        } else {

		            $name = $name;

		            if ($settings['storage_setting'] == 'local') {

		                \Storage::disk()->putFileAs(
		                    $path,
		                    $request->file($key_name)[$data_key][$key_name],
		                    $name
		                );


		                $path = $name;
		            } else if ($settings['storage_setting'] == 'wasabi') {

		                $path = \Storage::disk('wasabi')->putFileAs(
		                    $path,
		                    $file,
		                    $name
		                );

		                // $path = $path.$name;

		            } else if ($settings['storage_setting'] == 's3') {

		                $path = \Storage::disk('s3')->putFileAs(
		                    $path,
		                    $file,
		                    $name
		                );
		            }


		            $res = [
		                'flag' => 1,
		                'msg'  => 'success',
		                'url'  => $path
		            ];
		            return $res;
		        }
		    } else {
		        $res = [
		            'flag' => 0,
		            'msg' => __('Please set proper configuration for storage.'),
		        ];
		        return $res;
		    }
		} catch (\Exception $e) {
		    $res = [
		        'flag' => 0,
		        'msg' => $e->getMessage(),
		    ];
		    return $res;
		}
	}

    public static function saveSettings($data = [])
    {
        if(!empty($data)){
            foreach($data as $key => $value){

                LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
            }

            return true;
        }

        return false;
    }

    public static function get_google_fonts()
    {

        $fontArray = json_decode(file_get_contents('Modules/LandingPage/Resources/assets/assets/google_fonts.json'), true);
        $family = [];

        foreach ($fontArray as $font) {

            $family[] = $font['family'];
        }
        return $family;
    }

    public static function generateFontCSSFromJSON($family= null) 
    {
        $fontArray = json_decode(file_get_contents('Modules/LandingPage/Resources/assets/assets/google_fonts.json'), true);
        $cssRules = [];
    
        foreach ($fontArray as $font) {

            if ($family && $font['family'] !== $family) {
                continue;
            }

            $family = $font['family'];
            $files = $font['files'];
    
            foreach ($files as $variant => $url) {
                $fontWeight = ($variant == 'regular' || $variant == 'italic') ? '400' : '700';
                $fontStyle = ($variant == 'italic') ? 'italic' : 'normal';
    
                $cssRules[] = "@font-face {
                    font-family: '{$family}';
                    src: url('{$url}');
                    font-weight: {$fontWeight};
                    font-style: {$fontStyle};
                }";
            }
        }
    
        return implode("\n", $cssRules);
    }

    public static function pwa_store()
    {
        try {
            $pwa_data = \File::get(('uploads/customer_app/manifest.json'));

            $pwa_data = json_decode($pwa_data);
        } catch (\Throwable $th) {
            $pwa_data = [];
        }
        return $pwa_data;

    }

    //PixelField
    public static function pixel_plateforms()
    {
        $plateforms = [
            '' => 'Please select',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin' => 'Linkedin',
            'pinterest' => 'Pinterest',
            'quora' => 'Quora',
            'bing' => 'Bing',
            'google-adwords' => 'Google Adwords',
            'google-analytics' => 'Google Analytics',
            'snapchat' => 'Snapchat',
            'tiktok' => 'Tiktok',
        ];

        return $plateforms;
    }

    public static function qr_code()
    {
        $qr_type = [
            0 => 'Normal',
            2 => 'Text',
            4 =>'Image',
        ];

        return $qr_type ;
    }

    public static function addTermsAndPolicy()
    {
        
        $datas['template_name'] = 'page_content';
        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page']);

        $privacy_policy         = 1;
        $terms_and_conditions   = 1;

        if($data)
        {
            foreach ($data as $key => $value)
            {
                if($value->page_slug == 'terms_and_conditions')
                {
                    $terms_and_conditions = 0;
                }
    
                if($value->page_slug == 'privacy_policy')
                {
                    $privacy_policy = 0;
                }
            }
        }


        if($terms_and_conditions)
        {
            $terms['menubar_page_name'] = 'Terms and Conditions';
            $terms['template_name']     = 'page_content';
            $terms['page_slug']         = 'terms_and_conditions';
            $terms['menubar_page_short_description'] = 'Our Terms and Conditions page outlines user agreement, data protection policies, payment terms, and intellectual property rights.';
            $terms['menubar_page_contant'] = 'Service Agreement: Users agree to abide by the terms outlined in the Service Agreement, which governs the use of WorkDo Dash and its features.
            Data Protection: WorkDo Dash prioritizes user privacy and data protection, adhering to strict policies and regulations to safeguard sensitive information.
            Payment Terms: Users are responsible for adhering to the payment terms specified in the agreement, ensuring timely payments for services rendered.
            Intellectual Property Rights: WorkDo Dash respects intellectual property rights and expects users to do the same, refraining from infringing upon copyrights or trademarks associated with the platform.';
    
            $terms['header']    = 'on';
            $terms['footer']    = 'on';
            $terms['login']     = 'on';
            $data[]             = $terms;
        }

        if($privacy_policy)
        {
            $policy['menubar_page_name']    = 'Privacy Policy';
            $policy['page_slug']            = 'privacy_policy';
            $policy['template_name']         = 'page_content';
            $policy['menubar_page_short_description'] = 'Protecting your privacy is our priority at WorkDo Dash, ensuring your data is used transparently and securely.';
            $policy['menubar_page_contant'] = 'At WorkDo Dash, we prioritize your privacy and are committed to safeguarding your personal information. Our privacy policy outlines the types of data we collect, how we use it, and the measures we take to protect it. We collect information to enhance user experience, provide personalized services, and ensure the security of our platform. We do not share your personal data with third parties without your consent, except as required by law or to protect our rights. By using WorkDo Dash, you agree to the collection and use of information in accordance with this policy, ensuring transparency and trust in all our interactions.';
    
            $policy['header']    = 'on';
            $policy['footer']    = 'on';
            $policy['login']     = 'on';
            $data[]              = $policy;
        }
        
        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'],['value' => json_encode($data)]);

    }

}
