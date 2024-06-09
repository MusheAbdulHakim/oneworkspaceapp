<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('landingpage manage')){
            
            $settings = LandingPageSetting::settings();
            $reviews = json_decode($settings['reviews'], true) ?? [];
            return view('landingpage::landingpage.review.index', compact('settings','reviews'));

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
    public function store(Request $request)
    {
        //
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

    public function review_create(){
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.details.reviews.create');
    }

    public function review_store(Request $request){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['reviews'], true);

        $datas['review_header_tag']= $request->review_header_tag;
        $datas['review_heading']= $request->review_heading;
        $datas['review_description']= $request->review_description;
        $datas['review_live_demo_link']= $request->review_live_demo_link;
        $datas['review_live_demo_button_text']= $request->review_live_demo_button_text;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'reviews'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Review add successfully']);
    }

    public function review_edit($key){
        $settings = LandingPageSetting::settings();
        $reviews = json_decode($settings['reviews'], true);
        $review = $reviews[$key];
        return view('landingpage::landingpage.details.reviews.edit', compact('review','key'));
    }

    public function review_update(Request $request, $key){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['reviews'], true);

        $data[$key]['review_header_tag'] = $request->review_header_tag;
        $data[$key]['review_heading'] = $request->review_heading;
        $data[$key]['review_description'] = $request->review_description;
        $data[$key]['review_live_demo_link'] = $request->review_live_demo_link;
        $data[$key]['review_live_demo_button_text'] = $request->review_live_demo_button_text;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'reviews'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Review update successfully']);
    }

    public function review_delete($key){
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['reviews'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'reviews'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'review delete successfully']);
    }

}
