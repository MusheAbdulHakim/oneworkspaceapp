<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateBookmarkImage;
use App\Models\UrlBookmark;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class UrlBookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookmarks = UrlBookmark::latest()->get();
        return view('bookmarks.index', compact(
            'bookmarks'
        ));
    }

    public function cardView()
    {
        $bookmarks = UrlBookmark::latest()->get();
        return view('bookmarks.card', compact(
            'bookmarks'
        ));
    }

    public function iframeView($urlBookmark)
    {
        $bookmark = Crypt::decrypt($urlBookmark);
        return view('bookmarks.iframe', compact(
            'bookmark'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookmarks.create');
    }

    public function getMeta(Request $request)
    {
        try {
            $url  = $request->url;
            $meta = get_meta_tags($url);
            $title = $meta['title'] ?? getPageTitle($url);
            $description = $meta['description'] ?? '';
            return response()->json([
                'title' => $title,
                'description' => $description
            ]);
        } catch (\ErrorException $e) {
            return response()->json([
                'error' => 1,
                'title' => getPageTitle($url),
                'message' => "Meta data could not be obtained."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 1,
                'title' => getPageTitle($url),
                'message' => "Meta data could not be obtained."
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'title' => 'required',
        ]);
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('storage/bookmarks'), $imageName);
        }
        UrlBookmark::create([
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description,
            'color' => $request->color,
            'note' => $request->note,
            'type' => $request->bookmark_type,
            'image' => $imageName,
            'status' => !empty($request->status),
            'user_id' => auth()->user()->id,
            'order' => $request->order
        ]);
        // GenerateBookmarkImage::dispatch($bookmark);
        return redirect()->route('home')->with('success', 'Url has been bookmarked');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bookmark = UrlBookmark::findOrFail($id);
        return view('bookmarks.show', compact(
            'bookmark'
        ));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bookmark = UrlBookmark::findOrFail($id);
        return view('bookmarks.edit', compact(
            'bookmark'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bookmark = UrlBookmark::findOrFail($id);
        // if ($bookmark->url != $request->url) {
        //     GenerateBookmarkImage::dispatch($bookmark);
        // }
        $imageName = $bookmark->image;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('storage/bookmarks'), $imageName);
        }
        $bookmark->update([
            'url' => $request->url,
            'title' => $request->title,
            'description' => $request->description,
            'color' => $request->color,
            'note' => $request->note,
            'type' => $request->bookmark_type,
            'status' => !empty($request->status),
            'user_id' => auth()->user()->id,
            'image' => $imageName,
            'order' => $request->order,
        ]);
        return redirect()->route('home')->with('success', 'Bookmark has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        UrlBookmark::findOrFail($id)->delete();
        return redirect()->route('home')->with('success', 'Bookmark has been deleted');
    }
}
