<?php

namespace App\Services;

use App\Models\DocumentGroup;
use App\Models\User;
use ZipArchive;

class GroupDocumentService
{
    //  Dependency injection 
    public function __construct(
        private DocumentGenerationService $generator
    ) {}

    /**
     * This function take the group selected then get all the doc types with there fields,
     * flat the fields of each doc type 
     * filter the SYSTEM and AUTO types
     * remove deplucates keys 
     * re-index the collection 
     */
    public function getGroupFields(DocumentGroup $group): \Illuminate\Support\Collection
    {
        return $group->documentTypes()
            ->with('fields')
            ->get()
            ->flatMap(fn($dt) => $dt->fields)
            ->filter(fn($f) => in_array($f->field_type, ['INPUT', 'DATE', 'NUMBER', 'SELECT']))
            ->unique('field_key')
            ->values();
    }

    /**
     * Generates all documents in the group, zips them, returns zip path.
     */
    public function generateGroup(DocumentGroup $group, array $inputs, User $user): string
    {
        $paths = [];

        foreach ($group->documentTypes()->with('fields')->get() as $docType) {
            $paths[] = $this->generator->generate($docType, $inputs, $user);
        }

        return $this->zip($paths, $group->name);
    }

    private function zip(array $files, string $groupName): string
    {
        $dir = storage_path('app/generated');
        $zipName = str($groupName)->slug('_') . '_' . now()->format('YmdHisv') . '.zip';
        $zipPath = $dir . '/' . $zipName;

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create zip: {$zipPath}");
        }

        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        return $zipPath;
    }
}