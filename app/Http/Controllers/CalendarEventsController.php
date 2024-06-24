<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;

class CalendarEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = CalendarEvent::get();
        return view('calendar.index', compact(
            'events'
        ));
    }


    /**
     * Display a Calendar listing of the resource.
     */
    public function calendar()
    {
        $events = CalendarEvent::get()->map(function (CalendarEvent $model) {
            return [
                'id' => $model->id,
                'title' => $model->title,
                'description' => $model->description,
                'start' => $model->startDate,
                'end' => $model->endDate,
                'color' => $model->color,
                'url' => $model->url ?? '',
            ];
        });
        // dd($events->all());
        return view('calendar.calendar', compact(
            'events'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calendar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
        CalendarEvent::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'startDate' => $request->start,
            'endDate' => $request->end,
            'url' => $request->url,
            'color' => $request->color,
            'description' => $request->description,
        ]);
        return back()->with('success', 'Calendar Event has been successfully added');
    }

    /**
     * Display the specified resource.
     */
    public function show(CalendarEvent $mycalendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CalendarEvent $mycalendar)
    {
        $event = $mycalendar;
        return view('calendar.edit', compact(
            'event'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CalendarEvent $mycalendar)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
        $mycalendar->update([
            'title' => $request->title,
            'startDate' => $request->start,
            'endDate' => $request->end,
            'url' => $request->url,
            'color' => $request->color,
            'description' => $request->description,
        ]);
        return back()->with('success', 'Calendar Event has been successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalendarEvent $mycalendar)
    {
        $mycalendar->delete();
        return back()->with('success', 'Event has been deleted');
    }
}
