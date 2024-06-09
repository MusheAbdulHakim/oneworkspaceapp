<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Travel;
use Modules\Hrm\Events\CreateTrip;
use Modules\Hrm\Events\DestroyTrip;
use Modules\Hrm\Events\UpdateTrip;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('travel manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $travels = Travel::where('user_id', '=', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
            } else {
                $travels = Travel::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            }
            return view('hrm::travel.index', compact('travels'));
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
        if (Auth::user()->isAbleTo('travel create')) {
            $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            return view('hrm::travel.create', compact('employees'));
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
        if (Auth::user()->isAbleTo('travel create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'start_date' => 'required|after:yesterday',
                    'end_date' => 'required|after_or_equal:start_date',
                    'purpose_of_visit' => 'required',
                    'place_of_visit' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $travel                   = new Travel();
            $employee = Employee::where('user_id', '=', $request->employee_id)->first();
            if (!empty($employee)) {
                $travel->employee_id = $employee->id;
            }
            $travel->user_id          = $request->employee_id;
            $travel->start_date       = $request->start_date;
            $travel->end_date         = $request->end_date;
            $travel->purpose_of_visit = $request->purpose_of_visit;
            $travel->place_of_visit   = $request->place_of_visit;
            $travel->description      = $request->description;
            $travel->workspace        = getActiveWorkSpace();
            $travel->created_by       = creatorId();
            $travel->save();
            event(new CreateTrip($request, $travel));
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Trip']) && $company_settings['Employee Trip']  == true) {
                $User        = User::where('id', $travel->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                $uArr = [
                    'employee_trip_name' => $User->name,
                    'purpose_of_visit'  => $request->purpose_of_visit,
                    'start_date'  => $request->start_date,
                    'end_date'  => $request->end_date,
                    'place_of_visit' => $request->place_of_visit,
                    'trip_description' => $request->description,
                ];
                try {

                    $resp = EmailTemplate::sendEmailTemplate('Employee Trip', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('trip.index')->with('success', __('Trip successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('trip.index')->with('success', __('Trip successfully created.'));
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
    public function edit($id)
    {
        $travel = Travel::find($id);
        if (Auth::user()->isAbleTo('travel edit')) {
            if ($travel->created_by == creatorId() && $travel->workspace == getActiveWorkSpace()) {
                $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
                return view('hrm::travel.edit', compact('travel', 'employees'));
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
    public function update(Request $request, $id)
    {
        $travel = Travel::find($id);

        if (Auth::user()->isAbleTo('travel edit')) {
            if ($travel->created_by == creatorId() && $travel->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'start_date' => 'required|date',
                        'end_date' => 'required|after_or_equal:start_date',
                        'purpose_of_visit' => 'required',
                        'place_of_visit' => 'required',
                        'description' => 'required',

                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                if (!empty($employee)) {
                    $travel->employee_id = $employee->id;
                }
                $travel->user_id          = $request->employee_id;
                $travel->start_date       = $request->start_date;
                $travel->end_date         = $request->end_date;
                $travel->purpose_of_visit = $request->purpose_of_visit;
                $travel->place_of_visit   = $request->place_of_visit;
                $travel->description      = $request->description;
                $travel->save();
                event(new UpdateTrip($request, $travel));

                return redirect()->route('trip.index')->with('success', __('Trip successfully updated.'));
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
    public function destroy($id)
    {
        $travel = Travel::find($id);
        if (Auth::user()->isAbleTo('travel delete')) {
            if ($travel->created_by == creatorId() && $travel->workspace == getActiveWorkSpace()) {
                event(new DestroyTrip($travel));
                $travel->delete();

                return redirect()->route('trip.index')->with('success', __('Trip successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
