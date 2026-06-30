<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use App\Models\GeneratedDocument;
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
            ->map(fn ($d) => [
                'id' => $d->id,
                'name' => $d->template_name,
                'groups' => $d->groups->pluck('name'),
            ]);

        return response()->json($documents);
    }

    public function fields(DocumentType $document)
    {
        $this->authorizeAccess($document);

        $fields = $document->fields()
            ->get()
            ->filter(fn ($field) => in_array($field->field_type, ['INPUT', 'DATE', 'NUMBER', 'SELECT']))
            ->map(fn ($field) => $this->fieldPayload($field))
            ->values();

        return response()->json([
            'document' => $document->template_name,
            'fields' => $fields,
        ]);
    }

    public function generate(Request $request, DocumentType $document)
    {
        $this->authorizeAccess($document);

        $rules = [];
        foreach ($document->fields as $field) {
            if (! in_array($field->field_type, ['INPUT', 'DATE', 'NUMBER', 'SELECT'])) {
                continue;
            }

            $fieldRules = [$field->is_required ? 'required' : 'nullable'];

            match ($field->field_type) {
                'DATE' => $fieldRules[] = 'date',
                'NUMBER' => $fieldRules[] = 'numeric',
                'SELECT' => isset($this->fieldConfig($field)['options'])
                    ? $fieldRules[] = 'in:'.implode(',', $this->fieldConfig($field)['options'])
                    : null,
                default => null,
            };

            $rules[$field->field_key] = $fieldRules;
        }

        $validated = $request->validate($rules);

        try {
            app(DocumentGenerationService::class)->generate($document, $validated, auth()->user());

            return response()->json([
                'message' => 'Document generated successfully',
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

        $path = storage_path('app/'.$generated->generated_path);

        if (! file_exists($path)) {
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
            ->get()
            ->map(fn (GeneratedDocument $document) => [
                'id' => $document->id,
                'document_type_id' => $document->document_type_id,
                'document' => $document->documentType?->template_name ?? 'Unknown document',
                'generated_name' => $document->generated_name,
                'version' => $document->version,
                'status' => $document->status,
                'created_at' => $document->created_at?->toISOString(),
                'can_download' => $document->status === 'SUCCESS' && filled($document->generated_path),
            ]);

        return response()->json($documents);
    }

    public function downloadGenerated(GeneratedDocument $generated)
    {
        abort_unless($generated->user_id === auth()->id(), 403);
        abort_unless($generated->status === 'SUCCESS' && filled($generated->generated_path), 404);

        $path = storage_path('app/'.$generated->generated_path);

        if (! file_exists($path)) {
            abort(404);
        }

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

    private function fieldPayload($field): array
    {
        return [
            'key' => $field->field_key,
            'type' => $field->field_type,
            'required' => $field->is_required,
            'options' => $this->fieldOptions($field),
        ];
    }

    private function fieldOptions($field): ?array
    {
        if ($field->field_type !== 'SELECT') {
            return null;
        }

        $config = $this->fieldConfig($field);

        if (isset($config['min'], $config['max'])) {
            $max = $config['max'] === 'current_year' ? now()->year : $config['max'];

            return range((int) $max, (int) $config['min']);
        }

        return $config['options'] ?? [];
    }

    private function fieldConfig($field): array
    {
        $config = $field->config_json ?? [];

        if (is_string($config)) {
            $config = json_decode($config, true) ?? [];
        }

        return is_array($config) ? $config : [];
    }
}
