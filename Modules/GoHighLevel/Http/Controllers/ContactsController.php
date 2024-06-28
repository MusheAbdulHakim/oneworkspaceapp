<?php

namespace Modules\GoHighLevel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class ContactsController extends Controller
{
    public $apiVersion = '2021-07-28';

    public function index()
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client)) {
                $contacts = $client->withVersion($this->apiVersion)
                    ->make()
                    ->contacts()->list($access->locationId);
                return view('gohighlevel::contacts.index', compact(
                    'contacts'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    public function appointments(Request $request, $contactId)
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($acccess)) {
                $appointments = $client->withVersion($this->apiVersion)
                    ->make()
                    ->contacts()
                    ->appointments()
                    ->contacts($contactId);
                return view('gohighlevel::contacts.appointments', compact(
                    'appointments'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    public function notes(Request $request, $contactId)
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {
                $slots = $client->withVersion($this->apiVersion)
                    ->make()->contacts()->notes()->list($contactId);

                return view('gohighlevel::contacts.notes', compact(
                    'slots'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\MusheAbdulHakim\GoHighLevel\Exceptions\ErrorException $e) {
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }

    public function tasks(Request $request, $contactId)
    {
        try {

            $helper = (new GohighlevelHelper());
            $user = auth()->user();
            $client = $helper->SubAccountClient($user);
            $access = $helper->subAccountAccess($user);
            if (!empty($client) && !empty($access)) {

                $slots = $client->withVersion('2021-04-15')
                    ->make()->contacts()->tasks()->list($contactId);

                return view('gohighlevel::contacts.tasks', compact(
                    'slots'
                ));
            }
            return redirect()->route('settings.index')->with('error', 'Gohighlevel is not enabled for your account please contact the administrator');
        } catch (\Exception $e) {
            Log::alert($e->getMessage());
            return back()->with('error', 'Token has expired please authenticate GoHighLevel in the settings');
        }
    }
}
