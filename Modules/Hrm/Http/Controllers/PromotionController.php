<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Designation;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Promotion;
use Modules\Hrm\Events\CreatePromotion;
use Modules\Hrm\Events\DestroyPromotion;
use Modules\Hrm\Events\UpdatePromotion;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('promotion manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $promotions = Promotion::where('user_id', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            } else {
                $promotions = Promotion::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with('designation')->get();
            }
            return view('hrm::promotion.index', compact('promotions'));
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
        if (Auth::user()->isAbleTo('promotion create')) {
            $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            return view('hrm::promotion.create', compact('employees', 'designations'));
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
        if (Auth::user()->isAbleTo('promotion create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'designation_id' => 'required',
                    'promotion_title' => 'required',
                    'promotion_date' => 'required|after:yesterday',
                    'description' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $promotion                  = new Promotion();
            $employee = Employee::where('user_id', '=', $request->employee_id)->first();
            if (!empty($employee)) {
                $promotion->employee_id = $employee->id;
            }
            $promotion->user_id         = $request->employee_id;
            $promotion->designation_id  = $request->designation_id;
            $promotion->promotion_title = $request->promotion_title;
            $promotion->promotion_date  = $request->promotion_date;
            $promotion->description     = $request->description;
            $promotion->workspace       = getActiveWorkSpace();
            $promotion->created_by      = creatorId();
            $promotion->save();

            event(new CreatePromotion($request, $promotion));
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Promotion']) && $company_settings['Employee Promotion']  == true) {

                $User           = User::where('id', $promotion->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                $designation    = Designation::find($promotion->designation_id);

                $uArr = [
                    'employee_promotion_name' => $User->name,
                    'promotion_designation'  => $designation->name,
                    'promotion_title'  => $request->promotion_title,
                    'promotion_date'  => $request->promotion_date,
                ];
                try {

                    $resp = EmailTemplate::sendEmailTemplate('Employee Promotion', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('promotion.index')->with('success', __('Promotion  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('promotion.index')->with('success', __('Promotion  successfully created.'));
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
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Promotion $promotion)
    {
        if (Auth::user()->isAbleTo('promotion edit')) {
            if ($promotion->created_by == creatorId() && $promotion->workspace == getActiveWorkSpace()) {
                $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

                $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

                return view('hrm::promotion.edit', compact('promotion', 'employees', 'designations'));
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
    public function update(Request $request, Promotion $promotion)
    {
        if (Auth::user()->isAbleTo('promotion edit')) {
            if ($promotion->created_by == creatorId() && $promotion->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'designation_id' => 'required',
                        'promotion_title' => 'required',
                        'promotion_date' => 'required|date',
                        'description' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                if (!empty($employee)) {
                    $promotion->employee_id = $employee->id;
                }
                $promotion->user_id         = $request->employee_id;
                $promotion->designation_id  = $request->designation_id;
                $promotion->promotion_title = $request->promotion_title;
                $promotion->promotion_date  = $request->promotion_date;
                $promotion->description     = $request->description;
                $promotion->save();

                event(new UpdatePromotion($request, $promotion));

                return redirect()->route('promotion.index')->with('success', __('Promotion successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Promotion $promotion)
    {
        if (Auth::user()->isAbleTo('promotion delete')) {
            if ($promotion->created_by == creatorId() && $promotion->workspace == getActiveWorkSpace()) {
                event(new DestroyPromotion($promotion));

                $promotion->delete();

                return redirect()->route('promotion.index')->with('success', __('Promotion successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
