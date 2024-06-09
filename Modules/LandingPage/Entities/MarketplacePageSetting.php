<?php

namespace Modules\LandingPage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MarketplacePageSetting extends Model
{
    use HasFactory;

    protected $table = 'marketplace_page_settings';

    protected $fillable = [
        'name',
        'value',
        'module',
    ];
    protected static function newFactory()
    {
        return \Modules\LandingPage\Database\factories\MarketplacePageSettingFactory::new();
    }
    public static function settings($slug=null)
    {
        if(!isset($slug)){
            $data = MarketplacePageSetting::get();
        }
        else{
            $data = MarketplacePageSetting::where('module','=',$slug)->get();
        }

        $settings = [

            "product_main_banner" => "",
            "product_main_status" => "on",
            "product_main_heading" => "",
            "product_main_description" => "",
            "product_main_demo_link" => "",
            "product_main_demo_button_text" => "",

            "dedicated_theme_heading" => "",
            "dedicated_theme_description" => "",
            "dedicated_theme_sections" => "",
            "dedicated_theme_sections_heading" => "",
            "dedicated_theme_section_image" => "",


            "screenshots" => "",

            "addon_heading" => "",
            "addon_description" => "",
            "addon_section_status" => "on",


            "whychoose_heading" => "",
            "whychoose_description" => "",
            "pricing_plan_heading" => "",
            "pricing_plan_description" => "",
            "pricing_plan_demo_link" => "",
            "pricing_plan_demo_button_text" => "",
            "whychoose_sections_details" => "",
            "pricing_plan_text" => "",
            "whychoose_sections_status" => "on",
            "dedicated_theme_section_status" => "on",
            "screenshots_section_section" => "on",
            "dedicated_theme_section_heading"=>"",
        ];

        foreach($data as $row)
        {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }
}
