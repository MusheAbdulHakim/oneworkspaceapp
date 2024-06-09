<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\HelpdeskConversion;
use App\Models\HelpdeskTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpdeskConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$ticket_id)
    {
        $ticket = HelpdeskTicket::find($ticket_id);

        if(Auth::check())
        {
            $user        = User::where('id', Auth::user()->id)->first();
        }
        else
        {
            $user        = User::where('id', $ticket->created_by)->first();
        }

        if ($ticket) {
            $validation = ['reply_description' => ['required']];
            $validator = \Validator::make(
                $request->all(),
                $validation
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->withInput()->with('error', $messages->first());
            }
            $post = [];
            $post['sender'] = ($user) ? $user->id : 'user';
            $post['ticket_id'] = $ticket->id;
            $post['description'] = $request->reply_description;
            $data = [];
            if ($request->hasfile('reply_attachments')) {
                foreach ($request->file('reply_attachments') as $file) {
                    $name = $file->getClientOriginalName();
                    $data[] = [
                        'name' => $name,
                        'path' => 'uploads/helpdesk/' . $ticket->ticket_id . '/' . $name
                    ];
                    multi_upload_file($file, 'reply_attachments', $name, 'helpdesk/' . $ticket->ticket_id);
                }
            }
            $post['attachments'] = json_encode($data);
            HelpdeskConversion::create($post);
            $user        = User::where('id', $ticket->created_by)->first();
            $uArr = [
                'ticket_name' => $ticket->name,
                'ticket_id' => $ticket->ticket_id,
                'email' => $ticket->email,
                'reply_description' => $request->reply_description,

            ];

            $error_msg = EmailTemplate::sendEmailTemplate('New Helpdesk Ticket Reply', [$ticket->email], $uArr);
            return redirect()->back()->with('success', __('Reply added successfully') . ((isset($error_msg['error'])) ? '<br> <span class="text-danger">' . $error_msg['error'] . '</span>' : ''));
        } else {
            return view('403');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HelpdeskConversion  $helpdeskConversion
     * @return \Illuminate\Http\Response
     */
    public function show(HelpdeskConversion $helpdeskConversion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HelpdeskConversion  $helpdeskConversion
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpdeskConversion $helpdeskConversion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HelpdeskConversion  $helpdeskConversion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HelpdeskConversion $helpdeskConversion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HelpdeskConversion  $helpdeskConversion
     * @return \Illuminate\Http\Response
     */
    public function destroy(HelpdeskConversion $helpdeskConversion)
    {
        //
    }
}
