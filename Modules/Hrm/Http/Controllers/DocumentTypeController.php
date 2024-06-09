<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\DocumentType;
use Modules\Hrm\Events\CreateDocumentType;
use Modules\Hrm\Events\DestroyDocumentType;
use Modules\Hrm\Events\UpdateDocumentType;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('documenttype manage')) {
            $document_types = DocumentType::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get();
            return view('hrm::document_type.index', compact('document_types'));
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
        if (Auth::user()->isAbleTo('documenttype create')) {
            return view('hrm::document_type.create');
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
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:30',
                    'is_required' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $document_type              = new DocumentType();
            $document_type->name        = $request->name;
            $document_type->is_required = $request->is_required;
            $document_type->workspace   = getActiveWorkSpace();
            $document_type->created_by  = creatorId();
            $document_type->save();

            event(new CreateDocumentType($request, $document_type));

            return redirect()->route('document-type.index')->with('success', __('Document type successfully created.'));
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
    public function edit(DocumentType $document_type)
    {
        if (Auth::user()->isAbleTo('documenttype edit')) {
            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                return view('hrm::document_type.edit', compact('document_type'));
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
    public function update(Request $request, DocumentType $document_type)
    {
        if (Auth::user()->isAbleTo('documenttype edit')) {
            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:30',
                        'is_required' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $document_type->name        = $request->name;
                $document_type->is_required = $request->is_required;
                $document_type->save();

                event(new UpdateDocumentType($request, $document_type));

                return redirect()->route('document-type.index')->with('success', __('Document type successfully updated.'));
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
    public function destroy(DocumentType $document_type)
    {
        if (Auth::user()->isAbleTo('documenttype delete')) {

            if ($document_type->created_by == creatorId() && $document_type->workspace == getActiveWorkSpace()) {
                event(new DestroyDocumentType($document_type));

                $document_type->delete();

                return redirect()->route('document-type.index')->with('success', __('Document type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
