<?php

namespace Modules\ProductService\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\ChartOfAccountType;
use Modules\ProductService\Entities\Category;
use Modules\ProductService\Entities\ProductService;
use Modules\ProductService\Events\CreateCategory;
use Modules\ProductService\Events\DestroyCategory;
use Modules\ProductService\Events\UpdateCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('category create'))
        {
            $product_categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type',0)->get();
            $income_categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type',1)->get();
            $expance_categories = \Modules\ProductService\Entities\Category::where('created_by', '=', creatorId())->where('workspace_id',getActiveWorkSpace())->where('type',2)->get();
            $taxes = \Modules\ProductService\Entities\Tax::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get();
            $units = \Modules\ProductService\Entities\Unit::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get();

            return view('productservice::category.index',compact('product_categories','income_categories','expance_categories','taxes','units'));
        }
        else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {

        if(Auth::user()->isAbleTo('category create'))
        {
            $types = !empty($request->type) ? $request->type : 0;

            if($types == 1)
            {
                $chartAccountTypes  = ChartOfAccountType::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->whereIn('name', ['Income'])
                    ->get();
            }
            elseif($types == 2){
                $chartAccountTypes  = ChartOfAccountType::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->whereIn('name', ['Expenses'])
                    ->get();
            }
            else{
                $chartAccountTypes =[];
            }

            $chartAccounts = [];
            $subAccounts = [];

            foreach ($chartAccountTypes as $chartAccountType)
            {
                $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('parent', '=', 0)
                    ->where('type', '=', $chartAccountType->id)
                    ->where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())->get()
                    ->pluck('code_name', 'id');
                $chartAccounts->prepend('Select Account', 0);

                $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_account_parents.account');
                $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
                $subAccounts->where('chart_of_accounts.parent', '!=', 0);
                $subAccounts->where('chart_of_accounts.type', '=', $chartAccountType->id);
                $subAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
                $subAccounts->where('chart_of_accounts.created_by', creatorId());
                $subAccounts = $subAccounts->get()->toArray();
            }

            return view('productservice::category.create',compact('types','chartAccounts','subAccounts'));
        }
        else
        {
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

        if(Auth::user()->isAbleTo('category create'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:20',
                    'type' => 'required',
                    'color' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new Category();
            $category->name       = $request->name;
            $category->color      = $request->color;
            $category->type       = $request->type;
            $category->chart_account_id = !empty($request->chart_account_id) ? $request->chart_account_id : 0;
            $category->created_by = creatorId();
            $category->workspace_id =  getActiveWorkSpace();
            $category->save();

            event(new CreateCategory($request,$category));

            return redirect()->back()->with('success', __('Category successfully created.'));
        }
        else{
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
        return redirect()->route('category.index')->with('error', __('Permission denied.'));
        return view('productservice::category.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        if(Auth::user()->isAbleTo('category edit'))
        {
            $category = Category::find($id);

            if($category->type == 1)
            {

                $chartAccountTypes  = ChartOfAccountType::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->whereIn('name', ['Income'])
                    ->get();
            }
            elseif($category->type == 2){
                $chartAccountTypes  = ChartOfAccountType::where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())
                    ->whereIn('name', ['Expenses'])
                    ->get();
            }
            else{
                $chartAccountTypes =[];
            }


            $chartAccounts = [];
            $subAccounts = [];

            foreach ($chartAccountTypes as $chartAccountType)
            {
                $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('parent', '=', 0)
                    ->where('type', '=', $chartAccountType->id)
                    ->where('workspace', getActiveWorkSpace())
                    ->where('created_by', creatorId())->get()
                    ->pluck('code_name', 'id');
                $chartAccounts->prepend('Select Account', 0);


                $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_account_parents.account');
                $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
                $subAccounts->where('chart_of_accounts.parent', '!=', 0);
                $subAccounts->where('chart_of_accounts.type', '=', $chartAccountType->id);
                $subAccounts->where('chart_of_accounts.workspace', getActiveWorkSpace());
                $subAccounts->where('chart_of_accounts.created_by', creatorId());
                $subAccounts = $subAccounts->get()->toArray();
            }

            return view('productservice::category.edit', compact('category','chartAccounts','subAccounts'));
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
        if(Auth::user()->isAbleTo('category edit'))
        {
            $category = Category::find($id);
            if($category->created_by == \Auth::user()->id)
            {
                $validator = \Validator::make(
                    $request->all(), [
                                        'name' => 'required|max:20',
                                        'color' => 'required',
                                    ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $category->name  = $request->name;
                $category->color = $request->color;
                $category->chart_account_id = !empty($request->chart_account_id) ? $request->chart_account_id : 0;
                $category->save();
                event(new UpdateCategory($request,$category));
                return redirect()->back()->with('success', __('Category successfully updated.'));
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
        if(Auth::user()->isAbleTo('category delete'))
        {
            $category = Category::find($id);
            $product_categorie=ProductService::where('category_id',$category->id)->get();
            if(count($product_categorie)==0){
                event(new DestroyCategory($category));
                $category->delete();
            }else{

            return redirect()->back()->with('error', __('This Category has Product. Please remove the Product from this Category.'));

            }
            return redirect()->back()->with('success', __('Category successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
