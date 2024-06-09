<?php

namespace Modules\ProductService\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ProductService\Entities\ProductService;

class ProductStockController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->isAbleTo('product&service manage'))
        {

            $productServices = ProductService::where('created_by', '=', creatorId())->where('workspace_id', '=', getActiveWorkSpace())->where('type', '=', 'product')->get();


            return view('productservice::productstock.index', compact('productServices'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('productservice::create');
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
        return redirect()->back();
        return view('productservice::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $productService = ProductService::find($id);
        if(\Auth::user()->isAbleTo('product&service edit'))
        {
            if($productService->created_by == creatorId())
            {
                return view('productservice::productstock.edit', compact('productService'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->isAbleTo('product&service edit'))
        {
            $productService = ProductService::find($id);
            $total = $productService->quantity + $request->quantity;

            if($productService->created_by == creatorId())
            {
                $productService->quantity   = $total;
                $productService->created_by = creatorId();
                $productService->save();

                //Product Stock Report
                $type        = 'manually';
                $type_id     = 0;
                $description = $request->quantity . '  ' . __('quantity added by manually');
                if(module_is_active('Account'))
                {
                    ProductService::addProductStock($productService->id, $request->quantity, $type, $description, $type_id);
                }

                return redirect()->route('productstock.index')->with('success', __('Product quantity updated manually.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
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

}
