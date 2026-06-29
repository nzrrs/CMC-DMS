<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentGroup;
use App\Services\GroupDocumentService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $userDocTypeIds = auth()->user()
            ->role
            ->documentTypes()
            ->pluck('document_types.id');

        $groups = DocumentGroup::with('documentTypes')
            ->get()
            ->filter(fn($g) => $g->documentTypes
                ->pluck('id')
                ->intersect($userDocTypeIds)
                ->isNotEmpty()
            )
            ->map(fn($g) => [
                'id'        => $g->id,
                'name'      => $g->name,
                'doc_count' => $g->documentTypes->count(),
            ])
            ->values();

        return response()->json($groups);
    }

    public function fields(DocumentGroup $group)
    {
        $this->authorizeAccess($group);

        $fields = app(GroupDocumentService::class)
            ->getGroupFields($group)
            ->map(fn($f) => [
                'key'      => $f->field_key,
                'type'     => $f->field_type,
                'required' => $f->is_required,
                'options'  => $f->config_json['options'] ?? null,
            ]);

        return response()->json([
            'group'  => $group->name,
            'fields' => $fields,
        ]);
    }

    public function generate(Request $request, DocumentGroup $group)
    {
        $this->authorizeAccess($group);

        $service = app(GroupDocumentService::class);

        $rules = [];
        foreach ($service->getGroupFields($group) as $field) {
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
            $zipPath = $service->generateGroup($group, $validated, auth()->user());

            // store per-user so download endpoint knows which file
            cache()->put(
                "group_zip_{$group->id}_" . auth()->id(),
                $zipPath,
                now()->addMinutes(10)
            );

            return response()->json([
                'message'      => 'Group generated successfully',
                'download_url' => route('api.groups.download', $group),
            ]);

        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'Generation failed'], 500);
        }
    }

    public function download(DocumentGroup $group)
    {
        $this->authorizeAccess($group);

        $path = cache()->get("group_zip_{$group->id}_" . auth()->id());

        if (!$path || !file_exists($path)) {
            return response()->json(['message' => 'No file ready, generate first'], 404);
        }

        return response()->download($path, basename($path));
    }

    private function authorizeAccess(DocumentGroup $group): void
    {
        $role = auth()->user()?->role;
        $groupIds = $group->documentTypes()->pluck('document_types.id');
        $userIds  = $role?->documentTypes()->pluck('document_types.id') ?? collect();
        abort_unless($groupIds->intersect($userIds)->isNotEmpty(), 403);
    }
}