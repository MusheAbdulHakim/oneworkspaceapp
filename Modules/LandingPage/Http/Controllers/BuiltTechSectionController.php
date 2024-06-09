<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;


class BuiltTechSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){

            $settings = LandingPageSetting::settings();
            $buildtech_card_details = json_decode($settings['buildtech_card_details'], true) ?? [];

            return view('landingpage::landingpage.buildtech.index', compact('settings','buildtech_card_details'));

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
        return view('landingpage::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function buildtech_store(Request $request)
    {
        $data['buildtech_section_status']= isset($request->buildtech_section_status) ? 'on' : 'off';
        $data['buildtech_heading']= $request->buildtech_heading;
        $data['buildtech_description']= $request->buildtech_description;
        
        foreach($data as $key => $value){

            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> 'BuiltTech Section update successfully']);
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
    public function edit($id)
    {
        return view('landingpage::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function buildtech_card_create(){
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.details.buildtech.card_create');
    }



    public function buildtech_card_store(Request $request)
    {

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['buildtech_card_details'], true);

        if( $request->buildtech_card_logo){
            $buildtech_card_logo = time()."-buildtech_card_logo." . $request->buildtech_card_logo->getClientOriginalExtension();
            $path = upload_file($request,'buildtech_card_logo',$buildtech_card_logo,'landing_page_image',[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['buildtech_card_logo'] = $path['url'];
        }

        $datas['buildtech_card_heading']= $request->buildtech_card_heading;
        $datas['buildtech_card_description']= $request->buildtech_card_description;
        $datas['buildtech_card_more_details_link']= $request->buildtech_card_more_details_link;
        $datas['buildtech_card_more_details_button_text']= $request->buildtech_card_more_details_button_text;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'buildtech_card_details'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Buildtech Card added successfully']);

    }


    
    public function buildtech_card_edit($key){

        $settings = LandingPageSetting::settings();
        $buildtech_cards = json_decode($settings['buildtech_card_details'], true);
        $buildtech_card = $buildtech_cards[$key];
        return view('landingpage::landingpage.details.buildtech.card_edit', compact('buildtech_card','key'));
    }



    public function buildtech_card_update(Request $request, $key){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['buildtech_card_details'], true);

        if( $request->buildtech_card_logo){
            $buildtech_card_logo = time()."-buildtech_card_logo." . $request->buildtech_card_logo->getClientOriginalExtension();
            $path = upload_file($request,'buildtech_card_logo',$buildtech_card_logo,'landing_page_image',[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }

            // old img delete
            if(!empty($data[$key]['buildtech_card_logo']) && strpos($data[$key]['buildtech_card_logo'],'avatar.png') == false && check_file($data[$key]['buildtech_card_logo']))
            {
                delete_file($data[$key]['buildtech_card_logo']);
            }

            // $datas['buildtech_card_logo'] = $buildtech_card_logo;
            $data[$key]['buildtech_card_logo'] = $path['url'];
        }

        $data[$key]['buildtech_card_heading'] = $request->buildtech_card_heading;
        $data[$key]['buildtech_card_description'] = $request->buildtech_card_description;
        $data[$key]['buildtech_card_more_details_link'] = $request->buildtech_card_more_details_link;
        $data[$key]['buildtech_card_more_details_button_text'] = $request->buildtech_card_more_details_button_text;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'buildtech_card_details'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'BuiltTech Card Updated successfully']);
    }



    public function buildtech_card_delete($key){

        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['buildtech_card_details'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'buildtech_card_details'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'BuiltTech Card delete successfully']);
    }

}
