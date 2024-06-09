<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationTemplateLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->isAbleTo('notification template manage')) {
            $notifications = Notification::where('type','!=','mail')->get()->groupBy('type');
            return view('notification_templates.index', compact('notifications'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, $lang = 'en')
    {
        if (Auth::user()->isAbleTo('notification template manage')) {
            $languages = languages();
            $notification = Notification::where('id', '=', $id)->first();

            $currTempLang = NotificationTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();
            if (!isset($currTempLang) || empty($currTempLang)) {
                $currTempLang = NotificationTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                if (!empty($currTempLang)) {
                    $currTempLang->lang = $lang;
                } else {
                    return redirect()->back()->with('error', __('Template Not Found.'));
                }
            }

            return view('notification_templates.show', compact('notification', 'languages', 'currTempLang'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function storeNotificationLang(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('notification template manage')) {
            $validator = \Validator::make(
                $request->all(), [
                    'content' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $notificationlLangTemplate = NotificationTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

            if (empty($notificationlLangTemplate)) {
                $notificationlLangTemplate = new NotificationTemplateLang();
                $notificationlLangTemplate->parent_id = $id;
                $notificationlLangTemplate->lang = $request['lang'];
                $notificationlLangTemplate->module = $request['module'];
                $notificationlLangTemplate->content = $request['content'];
                $notificationlLangTemplate->variables = $request['variables'];
                $notificationlLangTemplate->save();
            } else {
                $notificationlLangTemplate->content = $request['content'];
                $notificationlLangTemplate->save();
            }

            return redirect()->route(
                'manage.notification.language', [
                    $id,
                    $request->lang,
                ]
            )->with('success', __('Notification Template Detail successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
}
