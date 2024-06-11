<?php

namespace Modules\SideMenuBuilder\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SideMenuBuilder\Entities\SideMenuBuilder;
use Modules\SideMenuBuilder\Events\CreateSideMenuBuilder;
use Modules\SideMenuBuilder\Events\DestroySideMenuBuilder;
use Modules\SideMenuBuilder\Events\UpdateSideMenuBuilder;

class SideMenuBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('sidemenubuilder manage')) {
            $menu_builder = SideMenuBuilder::where('created_by', creatorId())
                ->where('workspace', getActiveWorkSpace())->with(['getParentIdByName', 'creatorName'])->get();
            $menus = SideMenuBuilder::$menus;
            $show_window = SideMenuBuilder::$show_window;

            return view('sidemenubuilder::index', compact('menu_builder', 'menus', 'show_window'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('sidemenubuilder create')) {
            $menus = SideMenuBuilder::$menus;
            $links = SideMenuBuilder::$links;
            $hrefs = SideMenuBuilder::$hrefs;
            $show_window = SideMenuBuilder::$show_window;
            $modules = SideMenuBuilder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('sidemenubuilder::create', compact('menus', 'links', 'hrefs', 'show_window', 'modules'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('sidemenubuilder create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'menu_type' => 'required|in:mainmenu,submenu',
                    'name' => 'required',
                    'link_type' => 'required|in:internal,external,hash',
                    'links' => 'required_if:link_type,internal',
                    'link' => 'required_if:link_type,external',
                    'window' => 'required',
                    'modules' => 'required_if:menu_type,submenu'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            $menu_builder = new SideMenuBuilder();
            if ($request->menu_type == 'mainmenu') {
                $menu_builder->menu_type = $request->menu_type;
                $menu_builder->name = $request->name;
                $menu_builder->position = '0';
                $menu_builder->link_type = $request->link_type;
                $menu_builder->parent_id = '0';
                if ($request->link_type == 'internal') {
                    $menu_builder->link = $request->links;
                } elseif ($request->link_type == 'external') {
                    $menu_builder->link = json_encode($request->link);
                } else {
                    $menu_builder->link = '#';
                }
                $menu_builder->window_type = $request->window;
                $menu_builder->icon = $request->icon;
                $menu_builder->workspace = getActiveWorkSpace();
                $menu_builder->created_by = creatorId();
            } else {
                $menu_builder->menu_type = $request->menu_type;
                $menu_builder->name = $request->name;
                $menu_builder->position = $request->position;
                $menu_builder->link_type = $request->link_type;
                $menu_builder->parent_id = $request->modules;
                if ($request->link_type == 'internal') {
                    $menu_builder->link = $request->links;
                } elseif ($request->link_type == 'external') {
                    $menu_builder->link = json_encode($request->link);
                } else {
                    $menu_builder->link = '#';
                }
                $menu_builder->window_type = $request->window;
                $menu_builder->icon = '';
                $menu_builder->workspace = getActiveWorkSpace();
                $menu_builder->created_by = creatorId();
            }

            if ($request->menu_type == 'submenu') {
                $convert_link = SideMenuBuilder::where('id', $request->modules)->first();
                $convert_link->link_type = 'hash';
                $convert_link->window_type = 'same_window';
                $convert_link->link = '#';
                $convert_link->save();
            }

            $menu_builder->save();

            event(new CreateSideMenuBuilder($request, $menu_builder));

            return redirect()->route('sidemenubuilder.index')->with('success', __('Menu Builder successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('sidemenubuilder edit')) {
            $menu_builder = SideMenuBuilder::find($id);
            if ($menu_builder->created_by == creatorId() && $menu_builder->workspace == getActiveWorkSpace()) {
                $menus = SideMenuBuilder::$menus;
                $links = SideMenuBuilder::$links;
                $hrefs = SideMenuBuilder::$hrefs;
                $show_window = SideMenuBuilder::$show_window;
                $modules = SideMenuBuilder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get();

                return view('sidemenubuilder::edit', compact('menu_builder', 'menus', 'links', 'hrefs', 'show_window', 'modules'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, SideMenuBuilder $sidemenubuilder)
    {
        if (Auth::user()->isAbleTo('sidemenubuilder edit')) {
            if ($sidemenubuilder->created_by == creatorId() && $sidemenubuilder->workspace = getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'menu_type' => 'required|in:mainmenu,submenu',
                        'name' => 'required',
                        'link_type' => 'required|in:internal,external,hash',
                        'links' => 'required_if:link_type,internal',
                        'link' => 'required_if:link_type,external',
                        'window' => 'required',
                        'modules' => 'required_if:menu_type,submenu',
                        'position' => 'required_if:menu_type,submenu'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('sidemenubuilder.index')->with('error', $messages->first());
                }

                if ($request->menu_type == 'mainmenu') {
                    $sidemenubuilder->menu_type = $request->menu_type;
                    $sidemenubuilder->name = $request->name;
                    $sidemenubuilder->position = '0';
                    $sidemenubuilder->link_type = $request->link_type;
                    $sidemenubuilder->parent_id = '0';
                    if ($request->link_type == 'internal') {
                        $sidemenubuilder->link = $request->links;
                    } elseif ($request->link_type == 'external') {
                        $sidemenubuilder->link = json_encode($request->link);
                    } else {
                        $sidemenubuilder->link = '#';
                    }
                    $sidemenubuilder->window_type = $request->window;
                    $sidemenubuilder->icon = $request->icon;
                    $sidemenubuilder->workspace = getActiveWorkSpace();
                    $sidemenubuilder->created_by = creatorId();
                } else {
                    $sidemenubuilder->menu_type = $request->menu_type;
                    $sidemenubuilder->name = $request->name;
                    $sidemenubuilder->position = $request->position;
                    $sidemenubuilder->link_type = $request->link_type;
                    $sidemenubuilder->parent_id = $request->modules;
                    if ($request->link_type == 'internal') {
                        $sidemenubuilder->link = $request->links;
                    } elseif ($request->link_type == 'external') {
                        $sidemenubuilder->link = json_encode($request->link);
                    } else {
                        $sidemenubuilder->link = '#';
                    }
                    $sidemenubuilder->window_type = $request->window;
                    $sidemenubuilder->icon = '';
                    $sidemenubuilder->workspace = getActiveWorkSpace();
                    $sidemenubuilder->created_by = creatorId();
                }

                if ($request->menu_type == 'submenu') {
                    $convert_link = SideMenuBuilder::where('id', $request->modules)->first();
                    $convert_link->link_type = 'hash';
                    $convert_link->window_type = 'same_window';
                    $convert_link->link = '#';
                    $convert_link->save();
                }

                $sidemenubuilder->save();

                event(new UpdateSideMenuBuilder($request, $sidemenubuilder));

                return redirect()->route('sidemenubuilder.index')->with('success', __('Menu builder updated successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id, SideMenuBuilder $sideMenuBuilder)
    {
        if (Auth::user()->isAbleTo('sidemenubuilder delete')) {
            $menu_builder = SideMenuBuilder::find($id);
            if ($menu_builder->created_by == creatorId() && $menu_builder->workspace == getActiveWorkSpace()) {

                $sub_menu = SideMenuBuilder::where('parent_id', $menu_builder->id)->get();

                event(new DestroySideMenuBuilder($menu_builder));

                $menu_builder->delete();
                foreach ($sub_menu as $key => $value) {
                    $value->delete();
                }

                return redirect()->route('sidemenubuilder.index')->with('success', __('Menu builder deleted successfully'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function iFrameData($id)
    {
        if (Auth::user()->isAbleTo('sidemenubuilder manage')) {
            $sideMenuBuilder = SideMenuBuilder::find($id);
            // if ((isset($sideMenuBuilder->created_by) && $sideMenuBuilder->created_by == creatorId()) && (isset($sideMenuBuilder->workspace) && $sideMenuBuilder->workspace == getActiveWorkSpace())) {
            try {
                $iframe = SideMenuBuilder::where('id', $id)->where('window_type', 'iframe')->first();
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', __('Permission denied.'));
            }

            if ($iframe == null) {
                return redirect()->route('sidemenubuilder.index');
            }

            return view('sidemenubuilder::iframe', compact('sideMenuBuilder', 'iframe'));
            // } else {
            //     return redirect()->back()->with('error', __('Permission denied.'));
            // }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
