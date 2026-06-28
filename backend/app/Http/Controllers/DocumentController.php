<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        $documents = $role->documentTypes()->get();
        return view('documents.index', compact('documents'));
    }

    public function show(DocumentType $document)
    {
        $fields = $document->fields()->get();
        return view('documents.show', compact('document', 'fields'));
    }

    public function generate(Request $request, DocumentType $document)
    {
        try {
            $file = app(DocumentGenerationService::class)
                ->generate($document, $request->all(), auth()->user());

            session(['download_path' => $file]);

            return redirect()->back()->with('success', 'Document generated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to generate document: ' . $e->getMessage()]);
        }
    }

    public function download()
    {
        $path = session('download_path');
        if (!$path || !file_exists($path)) {
            abort(404);
        }


        return response()->download($path, basename($path));
    }

    public function history()
    {
        $documents = auth()->user()
            ->generatedDocuments()
            ->with('documentType')
            ->latest()
            ->get();

        return view('documents.history', compact('documents'));
    }

    public function downloadGenerated(\App\Models\GeneratedDocument $generated)
    {
        if ($generated->user_id !== auth()->id())
            abort(403);

        $path = storage_path('app/' . $generated->generated_path);

        if (!file_exists($path))
            abort(404);

        return response()->download($path, basename($path));
    }
}
