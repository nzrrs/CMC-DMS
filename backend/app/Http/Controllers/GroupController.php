<?php

namespace App\Http\Controllers;

use App\Models\DocumentGroup;
use App\Services\GroupDocumentService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function show(DocumentGroup $group)
    {
        $this->authorizeGroupAccess($group);

        $fields = app(GroupDocumentService::class)->getGroupFields($group);

        return view('groups.show', compact('group', 'fields'));
    }

    public function generate(Request $request, DocumentGroup $group)
    {
        $this->authorizeGroupAccess($group);

        $service = app(GroupDocumentService::class);

        // Build validation rules from merged fields (same logic as DocumentController)
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
            session(['group_download_path' => $zipPath]);
            return redirect()->back()->with('success', 'Group generated successfully!');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->withErrors(['error' => 'Generation failed.']);
        }
    }

    public function download()
    {
        $path = session('group_download_path');

        if (!$path || !file_exists($path)) {
            abort(404);
        }

        return response()->download($path, basename($path));
    }

    private function authorizeGroupAccess(DocumentGroup $group): void
    {
        $role = auth()->user()?->role;

        // User must have access to at least one doc type in this group
        $groupDocTypeIds = $group->documentTypes()->pluck('document_types.id');
        $userDocTypeIds  = $role?->documentTypes()->pluck('document_types.id') ?? collect();

        abort_unless($groupDocTypeIds->intersect($userDocTypeIds)->isNotEmpty(), 403);
    }
}