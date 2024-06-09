<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable; 
use Illuminate\Http\Request;
use Modules\LandingPage\Entities\LandingPageSetting;
use Modules\LandingPage\Entities\Pixel;
use Illuminate\Routing\Controller;

class PixelController extends Controller
{
    public function index()
    {
	    if (!Schema::hasTable('landingpage_pixels')) {
		// Display a listing of the resource.
		$pixels = Pixel::all();
		return view('landingpage::landingpage.seo.index', compact('pixels'));
        }
    }

    public function create()
    {
        $pixals_platforms = LandingPageSetting::pixel_plateforms();

        return view('landingpage::landingpage.seo.create', compact('pixals_platforms'));
    }

    public function store(Request $request)
    {
        // Store a newly created resource in storage.
        $request->validate([
            'platform' => 'required',
            'pixel_id' => 'required',
        ]);

        Pixel::create($request->all());

        return redirect()->back()->with('success', 'Pixel created successfully');
    }

    public function show(Pixel $pixel)
    {
        // Display the specified resource.
        return view('landingpage::landingpage.seo.show', compact('pixel'));
    }

    public function edit(Pixel $pixel)
    {
        // Show the form for editing the specified resource.
        $pixals_platforms = []; // Add the logic to get platforms
        return view('landingpage::landingpage.seo.edit', compact('pixel', 'pixals_platforms'));
    }

    public function update(Request $request, Pixel $pixel)
    {
        // Update the specified resource in storage.
        $request->validate([
            'platform' => 'required',
            'pixel_id' => 'required',
        ]);

        $pixel->update($request->all());

        return redirect()->back()->with('success', 'Pixel updated successfully');
    }

    public function destroy($id)
    {
        $pixel = Pixel::find($id);
        $pixel->delete();

        return redirect()->back()->with('success', 'Pixel deleted successfully');
    }
}
