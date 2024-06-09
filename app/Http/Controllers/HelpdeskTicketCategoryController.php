<?php

namespace App\Http\Controllers;

use App\Models\HelpdeskTicket;
use App\Models\HelpdeskTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpdeskTicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory manage')) {
            $categories = HelpdeskTicketCategory::get();
            return view('ticket_category.index', compact('categories'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory create')) {
            return view('ticket_category.create');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory create')) {
            $validation = [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'color' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ];
            $request->validate($validation);

            $post = [
                'name' => $request->name,
                'color' => $request->color,
            ];

            HelpdeskTicketCategory::create($post);

            return redirect()->route('helpdeskticket-category.index')->with('success', __('Category created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HelpdeskTicketCategory  $helpdeskTicketCategory
     * @return \Illuminate\Http\Response
     */
    public function show(HelpdeskTicketCategory $helpdeskTicketCategory)
    {
        return redirect()->route('helpdeskticket-category.index')->with('error', __('Permission denied.'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HelpdeskTicketCategory  $helpdeskTicketCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory edit')) {
            $category = HelpdeskTicketCategory::find($id);
            return view('ticket_category.edit', compact('category'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HelpdeskTicketCategory  $helpdeskTicketCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory edit')) {
            $category        = HelpdeskTicketCategory::find($id);
            $category->name  = $request->name;
            $category->color = $request->color;
            $category->save();

            return redirect()->route('helpdeskticket-category.index')->with('success', __('Category updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HelpdeskTicketCategory  $helpdeskTicketCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('helpdesk ticketcategory delete')) {
            $tickets = HelpdeskTicket::where('category', $id)->get();
            if (count($tickets) == 0) {
                $category = HelpdeskTicketCategory::find($id);
                $category->delete();
                return redirect()->route('helpdeskticket-category.index')->with('success', __('Category deleted successfully'));
            } else {
                return redirect()->route('helpdeskticket-category.index')->with('error', __('This Category is Used on Ticket.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
