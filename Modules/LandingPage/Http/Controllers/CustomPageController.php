<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;
use Nwidart\Modules\Facades\Module;


class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = LandingPageSetting::settings();
            $pages = json_decode($settings['menubar_page'], true);
            $footer_sections_details = json_decode($settings['footer_sections_details'], true) ?? [];

            $font_familys = LandingPageSetting::get_google_fonts();
            $fontweights   = LandingPageSetting::$fontweight ;

            return view('landingpage::custom_page.index', compact('fontweights','font_familys','pages', 'settings','footer_sections_details'));
        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));

        }
    }

    public function custom()
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = LandingPageSetting::settings();
            $pages = json_decode($settings['menubar_page'], true);
            $footer_sections_details = json_decode($settings['footer_sections_details'], true) ?? [];

            $font_familys = LandingPageSetting::get_google_fonts();
            $fontweights   = LandingPageSetting::$fontweight ;

            return view('landingpage::landingpage.custom.index', compact('fontweights','font_familys','pages', 'settings','footer_sections_details'));
        }else{

            return redirect()->back()->with('error',__('Permission Denied!'));
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::custom_page.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $datas['template_name'] = $request->template_name;

        if (isset($request->template_name) && $request->template_name == 'page_url') {
            $datas['page_url'] = $request->page_url;
            $datas['menubar_page_contant'] = '';
        } else {
            $datas['page_url'] = '';
            $datas['menubar_page_contant'] = $request->menubar_page_contant;
        }
        if($request->login){
            $datas['login'] = 'on';
        }else{
            $datas['login'] = 'off';
        }

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page'], true);
        $page_slug = str_replace(' ', '_', strtolower($request->menubar_page_name));

        $datas['menubar_page_name'] = $request->menubar_page_name;
        $datas['menubar_page_short_description'] = $request->menubar_page_short_description;
        $datas['menubar_page_contant'] = $request->menubar_page_contant;
        $datas['page_slug'] = $page_slug;

        if($request->header){
            $datas['header'] = 'on';
        }else{
            $datas['header'] = 'off';
        }

        if($request->footer){
            $datas['footer'] = 'on';
        }else{
            $datas['footer'] = 'off';
        }

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Page add successfully']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($key)
    {

        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);
        $page = $pages[$key];
        return view('landingpage::custom_page.edit', compact('page', 'key'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $key)
    {
        $datas['template_name'] = $request->template_name;

        if (isset($request->template_name) && $request->template_name == 'page_url') {
            $datas['page_url'] = $request->page_url;
            $datas['menubar_page_contant'] = '';
        } else {
            $datas['page_url'] = '';
            $datas['menubar_page_contant'] = $request->menubar_page_contant;
        }

        if ($request->login) {
            $datas['login'] = 'on';
        } else {
            $datas['login'] = 'off';
        }

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['menubar_page'], true);
        $page_slug = str_replace(' ', '_', strtolower($request->menubar_page_name));
        $datas['menubar_page_name'] = $request->menubar_page_name;
        $datas['menubar_page_short_description'] = $request->menubar_page_short_description;
        $datas['menubar_page_contant'] = $request->menubar_page_contant;

        $datas['page_slug'] = $data[$key]['page_slug'];

        if($request->header){
            $datas['header'] = 'on';
        }else{
            $datas['header'] = 'off';
        }

        if($request->footer){
            $datas['footer'] = 'on';
        }else{
            $datas['footer'] = 'off';
        }
        
        $data[$key] = $datas;
        $data = json_encode($data);


        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'],['value' => $data]);
        return redirect()->back()->with(['success'=> 'Page Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'menubar_page'],['value' => $pages]);

        return redirect()->back()->with(['success'=> 'Page deleted successfully']);
    }


    public function customStore(Request $request)
    {
        if( $request->site_logo){
            $site_logo = time()."site_logo." . $request->site_logo->getClientOriginalExtension();
            $path = upload_file($request,'site_logo',$site_logo,'landing_page_image',[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }

            // old img delete
            if(!empty($data['site_logo']) && strpos($data['site_logo'],'avatar.png') == false && check_file($data['site_logo']))
            {
                delete_file($data['site_logo']);
            }

            $data['site_logo'] = $path['url'];
            foreach($data as $key => $value){

                LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
            }

            return redirect()->back()->with(['success'=> 'Logo added successfully']);
        }
        return redirect()->back()->with(['error'=> 'Site Logo Not Found!']);
    }

    public function customPage($slug)
    {
        $modules_all = Module::getByStatus(1);
        $modules = [];
        if(count($modules_all) > 0)
        {
            $modules = array_intersect_key(
                $modules_all,  // the array with all keys
                array_flip(array_rand($modules_all,(count($modules_all) <  6) ? count($modules_all) : 6 )) // keys to be extracted
            );
        }

        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['menubar_page'], true);

        if (module_is_active('LandingPage')) {
            $layout = 'landingpage::layouts.marketplace';
        } else {
            $layout = 'marketplace.marketplace';
        }

        foreach ($pages as $key => $page) {
            if (module_is_active('LandingPage')) {
                if($page['page_slug'] == $slug){
                    return view('landingpage::layouts.custompage', compact('layout','page', 'settings'));
                }
            }
            else{
                if($page['page_slug'] == 'terms_and_conditions' || $page['page_slug'] == 'privacy_policy')
                {
                    return view('custompage'.$page['page_slug'], compact('layout','page', 'settings'));
                }
            }
        }

        return view('marketplace.detail_not_found', compact('layout','modules','settings'));

    }

    public function googleFonts(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),[
                'body_fontfamily' => 'required',
                // 'body_fontweight' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $data['body_fontfamily']        = $request->body_fontfamily ;
        // $data['body_fontweight']        = $request->body_fontweight ;

        $check = LandingPageSetting::saveSettings($data);

        if($check){
            return redirect()->back()->with(['success'=> 'Google fonts setting saved successfully']);
        }else{
            return redirect()->back()->with(['error'=> 'Something went wrong']);
        }
    }

}
