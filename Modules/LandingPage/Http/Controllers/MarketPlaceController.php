<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;
use Modules\LandingPage\Entities\MarketplacePageSetting;
use Nwidart\Modules\Facades\Module;

class MarketPlaceController extends Controller
{

    public function marketplaceindex($slug = null)
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){

            $modules = getshowModuleList();

            if(!isset($slug)){
                $slug = ($modules[0]);
            }

            if(!in_array($slug , $modules))
            {
                return redirect()->back()->with(['error'=> 'Something Went Wrong!']);
            }

            $settings = MarketplacePageSetting::settings($slug);

            return view('landingpage::marketplace.product_main.index',compact('settings','slug','modules'));
        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }



    // *******************// Product Main Section Starts// ************************//

    public function productindex($slug = null)
    {

        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = MarketplacePageSetting::settings($slug);
            return view('landingpage::marketplace.product_main.index',compact('settings','slug'));

        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }

    public function product_main_store(Request $request , $slug)
    {

        $data['product_main_banner'] = "";

        if( $request->product_main_banner){
            $product_main_banner = time()."product_main_banner." . $request->product_main_banner->getClientOriginalExtension();
            $path = upload_file($request,'product_main_banner',$product_main_banner,'marketplace_image/'.$slug,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            // old img delete
            if(!empty($data['product_main_banner']) && strpos($data['product_main_banner'],'avatar.png') == false && check_file($data['product_main_banner']))
            {
                delete_file($data['product_main_banner']);
            }

            $data['product_main_banner'] = $path['url'];
        }

        $data['product_main_status']= 'on';
        // $data['product_main_heading']= $request->product_main_heading;
        $data['product_main_description']= $request->product_main_description;
        $data['product_main_demo_link']= $request->product_main_demo_link;
        $data['product_main_demo_button_text']= $request->product_main_demo_button_text;



        foreach($data as $key => $value){
            MarketplacePageSetting::updateOrCreate(
                [
                    'name' =>  $key,
                    'module' => $slug
                ],
                [
                    'value' => $value,
                ]);
        }

        return redirect()->back()->with(['success'=> 'Setting update successfully']);

    }

    // *******************// Product Main Section Ends// ************************//




    // *******************// Dedicated Section Starts// ************************//

    public function dedicatedindex($slug = null)
    {

        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = MarketplacePageSetting::settings($slug);
            $dedicated_theme_sections = json_decode($settings['dedicated_theme_sections'], true) ?? [];
            return view('landingpage::marketplace.dedicated.index',compact('settings','slug','dedicated_theme_sections'));

        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }

    public function dedicated_theme_header_store(Request $request , $slug)
    {

        $data['dedicated_theme_section_status']= 'on';
        $data['dedicated_theme_heading']= $request->dedicated_theme_heading;
        $data['dedicated_theme_description']= $request->dedicated_theme_description;

        foreach($data as $key => $value){
            MarketplacePageSetting::updateOrCreate(
                [
                    'name' =>  $key,
                    'module' => $slug
                ],
                [
                    'value' => $value,
                ]);
        }

        return redirect()->back()->with(['success'=> 'Details saved successfully']);

    }

    public function dedicated_theme_create($slug= null){

        $settings = MarketplacePageSetting::settings($slug);

        return view('landingpage::marketplace.dedicated.create',compact('slug','settings'));
    }

    public function dedicated_theme_store(Request $request , $slug)
    {

        $settings = MarketplacePageSetting::settings($slug);
        $data = json_decode($settings['dedicated_theme_sections'], true);

        $datas['dedicated_theme_section_image'] = "";

        if( $request->dedicated_theme_section_image){
            $dedicated_theme_section_image = time()."-dedicated_theme_section_image." . $request->dedicated_theme_section_image->getClientOriginalExtension();
            $path = upload_file($request,'dedicated_theme_section_image',$dedicated_theme_section_image,'marketplace_image/'.$slug,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['dedicated_theme_section_image'] = $path['url'];
        }

        $datas['dedicated_theme_section_status']= 'on';
        $datas['dedicated_theme_section_heading']= $request->dedicated_theme_section_heading;
        $datas['dedicated_theme_section_description']= $request->dedicated_theme_section_description;
        $datas['dedicated_theme_section_cards']= $request->dedicated_theme_section_cards;

        $data[] = $datas;
        $data = json_encode($data);

        MarketplacePageSetting::updateOrCreate(
            [
                'name' =>  'dedicated_theme_sections',
                'module' =>  $slug
            ],
            [
                'value' => $data
            ]);

        return redirect()->back()->with(['success'=> 'Section added successfully']);
    }

    public function dedicated_theme_edit($slug, $key){

        $settings = MarketplacePageSetting::settings($slug);
        $dedicated_themes = json_decode($settings['dedicated_theme_sections'], true);
        $dedicated_theme = $dedicated_themes[$key];
        return view('landingpage::marketplace.dedicated.edit', compact('dedicated_theme','key','slug'));
    }

    public function dedicated_theme_update(Request $request, $slug ,$key)
    {
        $settings = MarketplacePageSetting::settings($slug);
        $data = json_decode($settings['dedicated_theme_sections'], true);

        if( $request->dedicated_theme_section_image){
            $dedicated_theme_section_image = time()."-dedicated_theme_section_image." . $request->dedicated_theme_section_image->getClientOriginalExtension();
            $path = upload_file($request,'dedicated_theme_section_image',$dedicated_theme_section_image,'marketplace_image/'.$slug,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }

            // old img delete
            if(!empty($data[$key]['dedicated_theme_section_image']) && strpos($data[$key]['dedicated_theme_section_image'],'avatar.png') == false && check_file($data[$key]['dedicated_theme_section_image']))
            {
                delete_file($data[$key]['dedicated_theme_section_image']);
            }

            $data[$key]['dedicated_theme_section_image'] = $path['url'];
        }

        $data[$key]['dedicated_theme_section_heading'] = $request->dedicated_theme_section_heading;
        $data[$key]['dedicated_theme_section_description'] = $request->dedicated_theme_section_description;
        $data[$key]['dedicated_theme_section_cards'] = $request->dedicated_theme_section_cards;

        $data = json_encode($data);
        MarketplacePageSetting::updateOrCreate(
            [
                'name' =>  'dedicated_theme_sections',
                'module' =>  $slug
            ],
            [
                'value' => $data
            ]);

        return redirect()->back()->with(['success'=> 'Dedicated Theme Section update successfully']);
    }

    public function dedicated_theme_delete($slug , $key)
    {
        $settings = MarketplacePageSetting::settings($slug);
        $pages = json_decode($settings['dedicated_theme_sections'], true);
        unset($pages[$key]);
        MarketplacePageSetting::updateOrCreate(
            [
                'name' =>  'dedicated_theme_sections',
                'module' =>  $slug
            ],
            [
                'value' => $pages
            ]);
        return redirect()->back()->with(['success'=> 'Dedicated Theme Section delete successfully']);
    }

    // *******************// Dedicated Section ends// ************************//




    // *******************// Whychoose Section Starts// ************************//

    public function whychooseindex($slug = null)
    {

        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = MarketplacePageSetting::settings($slug);
            return view('landingpage::marketplace.whychoose.index',compact('settings','slug'));

        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }


    public function whychoose_store(Request $request , $slug)
    {
        $settings = MarketplacePageSetting::settings($slug);


        $data['whychoose_sections_status']= 'on';
        $data['whychoose_heading']= $request->whychoose_heading;
        $data['whychoose_description']= $request->whychoose_description;
        $data['pricing_plan_heading']= $request->pricing_plan_heading;
        $data['pricing_plan_description']= $request->pricing_plan_description;
        $data['pricing_plan_demo_link']= $request->pricing_plan_demo_link;
        $data['pricing_plan_demo_button_text']= $request->pricing_plan_demo_button_text;
        $data['whychoose_heading']= $request->whychoose_heading;
        $data['whychoose_heading']= $request->whychoose_heading;
        $data['pricing_plan_text']= json_encode($request->pricing_plan_text);

        foreach($data as $key => $value){
            MarketplacePageSetting::updateOrCreate(
                [
                    'name' =>  $key,
                    'module' => $slug
                ],
                [
                    'value' => $value,
                ]);
        }

        return redirect()->back()->with(['success'=> 'Footer Section Added successfully']);
    }


    // *******************// Whychoose Section Ends// ************************//





    // *******************// Screenshot Section Starts// ************************//

    public function screenshotindex($slug = null)
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){
            $settings = MarketplacePageSetting::settings($slug);
            $screenshots = json_decode($settings['screenshots'], true) ?? [];
            return view('landingpage::marketplace.screenshots.index',compact('screenshots','settings','slug'));

        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }

    public function screenshots_create($slug = null)
    {

        $settings = MarketplacePageSetting::settings($slug);

        return view('landingpage::marketplace.screenshots.create',compact('slug'));
    }

    public function screenshots_store(Request $request , $slug)
    {
        $settings = MarketplacePageSetting::settings($slug);
        $data = json_decode($settings['screenshots'], true);

        if( $request->screenshots){
            $screenshots = time()."-screenshots." . $request->screenshots->getClientOriginalExtension();
            $path = upload_file($request,'screenshots',$screenshots,'marketplace_image/'.$slug,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['screenshots'] = $path['url'];
        }

        $datas['screenshots_heading']= $request->screenshots_heading;

        $data[] = $datas;
        $data = json_encode($data);
        MarketplacePageSetting::updateOrCreate(['name' =>  'screenshots','module' =>  $slug],['value' => $data]);

        return redirect()->back()->with(['success'=> 'screenshots added successfully']);
    }

    public function screenshots_edit($slug ,$key){

        $settings = MarketplacePageSetting::settings($slug);
        $screenshots = json_decode($settings['screenshots'], true);
        $screenshot = $screenshots[$key];

        return view('landingpage::marketplace.screenshots.edit', compact('screenshot','key','slug'));
    }

    public function screenshots_update(Request $request, $slug , $key){

        $settings = MarketplacePageSetting::settings($slug);
        $data = json_decode($settings['screenshots'], true);

        if( $request->screenshots){
            $screenshots = time()."-screenshots." . $request->screenshots->getClientOriginalExtension();
            $path = upload_file($request,'screenshots',$screenshots,'marketplace_image/'.$slug,[]);

            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }

            // old img delete
            if(!empty($data[$key]['screenshots']) && strpos($data[$key]['screenshots'],'avatar.png') == false && check_file($data[$key]['screenshots']))
            {
                delete_file($data[$key]['screenshots']);
            }

            $data[$key]['screenshots'] = $path['url'];
        }

        $data[$key]['screenshots_heading'] = $request->screenshots_heading;

        $data = json_encode($data);
        MarketplacePageSetting::updateOrCreate(['name' =>  'screenshots' ,'module' =>  $slug],['value' => $data]);

        return redirect()->back()->with(['success'=> 'screenshots update successfully']);
    }

    public function screenshots_delete($slug , $key){
        $settings = MarketplacePageSetting::settings($slug);
        $pages = json_decode($settings['screenshots'], true);
        unset($pages[$key]);
        MarketplacePageSetting::updateOrCreate(['name' =>  'screenshots','module' =>  $slug],['value' => $pages]);

        return redirect()->back()->with(['success'=> 'Screenshots delete successfully']);
    }

    // *******************// Screenshot Section Ends// ************************//


    // *******************// Add-on Section Starts// ************************//

    public function addonindex($slug = null)
    {

        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = MarketplacePageSetting::settings($slug);
            return view('landingpage::marketplace.addon.index',compact('settings','slug'));

        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }

    }

    public function addon_store(Request $request , $slug)
    {
        $data['addon_section_status']= 'on';
        $data['addon_heading']= $request->addon_heading;
        $data['addon_description']= $request->addon_description;

        foreach($data as $key => $value){
            MarketplacePageSetting::updateOrCreate(
                [
                    'name' =>  $key,
                    'module' => $slug
                ],
                [
                    'value' => $value,
                ]);
        }

        return redirect()->back()->with(['success'=> 'Details saved successfully']);

    }

    // *******************// Add-on Section Ends// ************************//





}
