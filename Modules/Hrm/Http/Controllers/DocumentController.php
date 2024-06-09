<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\Role;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Document;
use Modules\Hrm\Events\CreateDocument;
use Modules\Hrm\Events\DestroyDocument;
use Modules\Hrm\Events\UpdateDocument;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('document manage')) {
            $documents = Document::where('workspace', getActiveWorkSpace())
                ->leftJoin('roles', 'documents.role', '=', 'roles.id')
                ->where('documents.created_by', creatorId())
                ->select('documents.*', 'roles.name as role_name')
                ->get();
            return view('hrm::document.index', compact('documents'));
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
        if (Auth::user()->isAbleTo('document create')) {
            $roles  = Role::where('created_by', creatorId())->whereNotIn('name', Auth::user()->not_emp_type)->get()->pluck('name', 'id');

            $roles->prepend('All', '0');
            return view('hrm::document.create', compact('roles'));
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
        if (Auth::user()->isAbleTo('documenttype create')) {
            $currentWorkspace = getActiveWorkSpace();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'documents' => 'required'
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if ($request->hasFile('documents')) {
                $filenameWithExt = $request->file('documents')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('documents')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'documents', $fileNameToStore, 'document');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            $document              = new Document();
            $document->name        = $request->name;
            $document->document    = !empty($request->documents) ? $url : '';
            $document->role        = $request->role;
            $document->description = $request->description;
            $document->workspace   = $currentWorkspace;
            $document->created_by  = creatorId();
            $document->save();

            event(new CreateDocument($request, $document));

            return redirect()->route('document.index')->with('success', __('Document Created Successfully!'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->route('document.index');
        return view('hrm::document.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Document $document)
    {
        if (Auth::user()->isAbleTo('document edit')) {
            $currentWorkspace = getActiveWorkSpace();
            if ($document->created_by == creatorId() && $document->workspace == $currentWorkspace) {
                $roles = Role::where('created_by', creatorId())->get()->pluck('name', 'id');
                $roles->prepend('All', '0');
                return view('hrm::document.edit', compact('document', 'roles'));
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
        if (Auth::user()->isAbleTo('document edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $document = Document::find($id);

            if (!empty($request->documents)) {
                $filenameWithExt = $request->file('documents')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('documents')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'documents', $fileNameToStore, 'document');
                if ($uplaod['flag'] == 1) {
                    if (!empty($document->document)) {
                        delete_file($document->document);
                    }
                    $url = $uplaod['url'];
                    $document->document = $url;
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            $document->name        = $request->name;
            $document->role        = $request->role;
            $document->description = $request->description;
            $document->save();

            event(new UpdateDocument($request, $document));

            return redirect()->route('document.index')->with('success', __('Document Successfully uploaded.'));
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
        if (Auth::user()->isAbleTo('document delete')) {
            $currentWorkspace = getActiveWorkSpace();
            $document = Document::find($id);
            if ($document->created_by == creatorId() && $document->workspace == $currentWorkspace) {
                if (!empty($document->document)) {
                    delete_file($document->document);
                }

                event(new DestroyDocument($document));

                $document->delete();
                return redirect()->route('document.index')->with('success', __('Document successfully deleted.'));
            } else {
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
