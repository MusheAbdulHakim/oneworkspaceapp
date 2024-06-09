<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{

    /**d
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('email template manage'))
        {
            $email_templates = EmailTemplate::get();

            return view('email_templates.index',compact('email_templates'));
        }
        else
        {
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
        //
        return view('email_templates.show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id ,$lang = 'en')
    {
        if(Auth::user()->isAbleTo('email template manage'))
        {
            $languages         = languages();
            $emailTemplate     = EmailTemplate::where('id', '=', $id)->first();
            $currEmailTempLang = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', $lang)->first();
            if(!isset($currEmailTempLang) || empty($currEmailTempLang))
            {
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                $currEmailTempLang       = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', 'en')->first();
                if(!empty($currEmailTempLang)){
                    $currEmailTempLang->lang = $lang;
                }else{
                    return redirect()->back()->with('error', __('Template Not Found.'));
                }
            }

            return view('email_templates.show', compact('emailTemplate', 'languages', 'currEmailTempLang'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->from = $request->from;
        $emailTemplate->save();
        return redirect()->back()->with('success', __('Email Template Detail successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Used For Store Email Template Language Wise
    public function storeEmailLang(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'subject' => 'required',
                                'content' => 'required',
                            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $emailLangTemplate = EmailTemplateLang::where('parent_id', '=', $id)->where('lang', '=', $request->lang)->first();

        // if record not found then create new record else update it.
        if(empty($emailLangTemplate))
        {
            $emailLangTemplate            = new EmailTemplateLang();
            $emailLangTemplate->parent_id = $id;
            $emailLangTemplate->lang      = $request['lang'];
            $emailLangTemplate->subject   = $request['subject'];
            $emailLangTemplate->content   = $request['content'];
            $emailLangTemplate->save();
        }
        else
        {
            $emailLangTemplate->subject = $request['subject'];
            $emailLangTemplate->content = $request['content'];
            $emailLangTemplate->save();
        }

        return redirect()->route(
            'manage.email.language', [
                                        $id,
                                        $request->lang,
                                    ]
        )->with('success', __('Email Template Detail successfully updated.'));

    }
}
