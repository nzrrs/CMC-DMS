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
        $documents = $user->role->documentTypes()->with('groups')->get();
        $groups = \App\Models\DocumentGroup::with('documentTypes')->get();
        return view('documents.index', compact('documents', 'groups'));
    }

    public function show(DocumentType $document)
    {
        $this->authorizeDocumentAccess($document);

        $fields = $document->fields()->get();
        return view('documents.show', compact('document', 'fields'));
    }

    public function generate(Request $request, DocumentType $document)
    {
        $this->authorizeDocumentAccess($document);

        // Validate input fields from document field definitions
        $rules = [];
        foreach ($document->fields as $field) {
            if (!in_array($field->field_type, ['INPUT', 'DATE', 'NUMBER', 'SELECT'])) {
                continue;
            }

            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            match ($field->field_type) {
                'DATE' => $fieldRules[] = 'date',
                'NUMBER' => $fieldRules[] = 'numeric',
                'SELECT' => isset($field->config_json['options'])
                ? $fieldRules[] = 'in:' . implode(',', $field->config_json['options'])
                : null,
                default => null,
            };

            $rules[$field->field_key] = $fieldRules;
        }

        $validated = $request->validate($rules);

        try {
            $file = app(DocumentGenerationService::class)
                ->generate($document, $validated, auth()->user());

            session(['download_path' => $file]);

            return redirect()->back()->with('success', 'Document generated successfully!');

        } catch (\Exception $e) {
            report($e);

            return redirect()->back()->withErrors([
                'error' => 'Une erreur est survenue lors de la génération du document.',
            ]);
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
        if ($generated->user_id !== auth()->id()) {
            abort(403);
        }

        if ($generated->status !== 'SUCCESS') {
            abort(404);
        }

        $path = storage_path('app/' . $generated->generated_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, basename($path));
    }

    private function authorizeDocumentAccess(DocumentType $document): void
    {
        $role = auth()->user()?->role;

        abort_unless(
            $role && $role->documentTypes()->whereKey($document->getKey())->exists(),
            403
        );
    }
}