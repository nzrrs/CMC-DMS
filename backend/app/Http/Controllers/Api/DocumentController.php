<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()
            ->role
            ->documentTypes()
            ->with('groups')
            ->get()
            ->map(fn($d) => [
                'id'            => $d->id,
                'name'          => $d->template_name,
                'groups'        => $d->groups->pluck('name'),
            ]);

        return response()->json($documents);
    }

    public function fields(DocumentType $document)
    {
        $this->authorizeAccess($document);

        $fields = $document->fields()
            ->get()
            ->map(fn($f) => [
                'key'       => $f->field_key,
                'type'      => $f->field_type,
                'required'  => $f->is_required,
                'options'   => $f->config_json['options'] ?? null,
            ]);

        return response()->json([
            'document' => $document->template_name,
            'fields'   => $fields,
        ]);
    }

    public function generate(Request $request, DocumentType $document)
    {
        $this->authorizeAccess($document);

        $rules = [];
        foreach ($document->fields as $field) {
            if (!in_array($field->field_type, ['INPUT', 'DATE', 'NUMBER', 'SELECT'])) continue;

            $fieldRules = [$field->is_required ? 'required' : 'nullable'];

            match ($field->field_type) {
                'DATE'   => $fieldRules[] = 'date',
                'NUMBER' => $fieldRules[] = 'numeric',
                'SELECT' => isset($field->config_json['options'])
                    ? $fieldRules[] = 'in:' . implode(',', $field->config_json['options'])
                    : null,
                default  => null,
            };

            $rules[$field->field_key] = $fieldRules;
        }

        $validated = $request->validate($rules);

        try {
            app(DocumentGenerationService::class)->generate($document, $validated, auth()->user());

            return response()->json([
                'message'      => 'Document generated successfully',
                'download_url' => route('api.documents.download', $document),
            ]);

        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Generation failed'], 500);
        }
    }

    public function download(DocumentType $document)
    {
        $this->authorizeAccess($document);

        $generated = auth()->user()
            ->generatedDocuments()
            ->where('document_type_id', $document->id)
            ->where('status', 'SUCCESS')
            ->whereNotNull('generated_path')
            ->latest()
            ->firstOrFail();

        $path = storage_path('app/' . $generated->generated_path);

        if (!file_exists($path)) abort(404);

        return response()->download($path, basename($path));
    }

    private function authorizeAccess(DocumentType $document): void
    {
        $role = auth()->user()?->role;
        abort_unless(
            $role && $role->documentTypes()->whereKey($document->getKey())->exists(),
            403
        );
    }
}