<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Holiday;
use Modules\Hrm\Events\CreateHolidays;
use Modules\Hrm\Events\DestroyHolidays;
use Modules\Hrm\Events\UpdateHolidays;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAbleTo('holiday manage')) {
            $holidays = Holiday::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
            if (!empty($request->start_date)) {
                $holidays->where('start_date', '>=', $request->start_date);
            }
            if (!empty($request->end_date)) {
                $holidays->where('end_date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();

            return view('hrm::holiday.index', compact('holidays'));
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
        if (Auth::user()->isAbleTo('holiday create')) {
            return view('hrm::holiday.create');
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
        if (Auth::user()->isAbleTo('holiday create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'occasion' => 'required',
                    'start_date' => 'required|after:yesterday',
                    'end_date' => 'required|after_or_equal:start_date',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday                    = new Holiday();
            $holiday->occasion          = $request->occasion;
            $holiday->start_date        = $request->start_date;
            $holiday->end_date          = $request->end_date;
            $holiday->workspace         = getActiveWorkSpace();
            $holiday->created_by        = creatorId();
            $holiday->save();

            event(new CreateHolidays($request, $holiday));

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully created.');
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
    public function edit(Holiday $holiday)
    {
        if (Auth::user()->isAbleTo('holiday edit')) {
            return view('hrm::holiday.edit', compact('holiday'));
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
    public function update(Request $request, Holiday $holiday)
    {
        if (\Auth::user()->isAbleTo('holiday edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'occasion' => 'required',
                    'start_date' => 'required|date',
                    'end_date' => 'required|after_or_equal:start_date',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $holiday->occasion          = $request->occasion;
            $holiday->start_date        = $request->start_date;
            $holiday->end_date          = $request->end_date;
            $holiday->save();
            event(new UpdateHolidays($request, $holiday));
            return redirect()->route('holiday.index')->with('success', 'Holiday successfully updated.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Holiday $holiday)
    {
        if (Auth::user()->isAbleTo('holiday delete')) {
            event(new DestroyHolidays($holiday));
            $holiday->delete();

            return redirect()->route('holiday.index')->with('success', 'Holiday successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function calender(Request $request)
    {
        if (Auth::user()->isAbleTo('holiday manage')) {
            $holidays = Holiday::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
            $today_date = date('m');
            $current_month_event = Holiday::select('occasion', 'start_date', 'end_date', 'created_at')->where('workspace', getActiveWorkSpace())
                ->whereRaw('MONTH(start_date) = ? AND MONTH(end_date) = ?', [date('m'), date('m')])
                ->get();
            if (!empty($request->start_date)) {
                $holidays->where('start_date', '>=', $request->start_date);
            }
            if (!empty($request->end_date)) {
                $holidays->where('end_date', '<=', $request->end_date);
            }
            $holidays = $holidays->get();

            $arrHolidays = [];

            foreach ($holidays as $holiday) {
                $arr['id']        = $holiday['id'];
                $arr['title']     = $holiday['occasion'];
                $arr['start']     = $holiday['start_date'];
                $arr['end']       = date('Y-m-d', strtotime($holiday['end_date'] . ' +1 day'));
                // add class in custom js ( open model using this class ) -> ex ( holiday-edit )
                $arr['className'] = 'event-danger holiday-edit';
                $arr['url']       = route('holiday.edit', $holiday['id']);
                $arrHolidays[]    = $arr;
            }
            $arrHolidays =  json_encode($arrHolidays);
            return view('hrm::holiday.calender', compact('arrHolidays', 'current_month_event'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileImportExport()
    {
        if (Auth::user()->isAbleTo('holiday import')) {
            return view('hrm::holiday.import');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function fileImport(Request $request)
    {
        if (Auth::user()->isAbleTo('holiday import')) {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="occasion">Occasion</option>
                                    <option value="start_date">Start Date</option>
                                    <option value="end_date">End Date</option>
                                    </select>
                                </th>
                                ';
                    }
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            echo json_encode($output);
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('holiday import')) {
            return view('hrm::holiday.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function holidayImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('holiday import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];

            unset($_SESSION['file_data']);

            $user = \Auth::user();


            foreach ($file_data as $row) {
                $holiday = Holiday::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->Where('occasion', 'like', $row[$request->occasion])->get();

                if ($holiday->isEmpty()) {

                    try {
                        Holiday::create([
                            'occasion' => $row[$request->occasion],
                            'start_date' => $row[$request->start_date],
                            'end_date' => $row[$request->end_date],
                            'created_by' => creatorId(),
                            'workspace' => getActiveWorkSpace(),
                        ]);
                    } catch (\Exception $e) {
                        $flag = 1;
                        $html .= '<tr>';

                        $html .= '<td>' . $row[$request->occasion] . '</td>';
                        $html .= '<td>' . $row[$request->start_date] . '</td>';
                        $html .= '<td>' . $row[$request->end_date] . '</td>';

                        $html .= '</tr>';
                    }
                } else {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->occasion] . '</td>';
                    $html .= '<td>' . $row[$request->start_date] . '</td>';
                    $html .= '<td>' . $row[$request->end_date] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

                return response()->json([
                    'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }
}
