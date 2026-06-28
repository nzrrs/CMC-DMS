<?php

namespace App\Services;

use App\Models\DocumentType;
use App\Models\GeneratedDocument;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentGenerationService
{
    public function generate(
        DocumentType $document,
        array $inputs,
        User $user
    ): string {

        $template = storage_path('app/' . $document->template_path);

        // Check if template file exists
        if (!file_exists($template)) {
            throw new \Exception("Template file not found: {$template}");
        }

        $processor = new TemplateProcessor($template);

        foreach ($document->fields as $field) {
            $value = $this->resolveFieldValue(
                $field,
                $inputs,
                $user
            );

            $processor->setValue(
                $field->field_key,
                $value
            );
        }

        $directory = storage_path('app/generated');

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $filename = str($document->template_name)
            ->slug('_')
            . '_'
            . now()->format('YmdHis')
            . '.docx';

        $path = $directory . '/' . $filename;

        DB::beginTransaction();

        try {
            $processor->saveAs($path);

            // Check if file was actually created
            if (!file_exists($path)) {
                throw new \Exception("Failed to save document to: {$path}");
            }

            GeneratedDocument::create([
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'generated_name' => $filename,
                'generated_path' => "generated/$filename",
                'version' => $this->nextVersion($user, $document),
                'status' => 'SUCCESS',
            ]);

            DB::commit();

            return $path;

        } catch (\Throwable $e) {
            DB::rollBack();
            
            // Log the error for debugging
            \Log::error('Document generation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'exception' => $e
            ]);

            // Still create a record for failed generation
            GeneratedDocument::create([
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'generated_name' => $filename,
                'generated_path' => "generated/$filename",
                'version' => $this->nextVersion($user, $document),
                'status' => 'FAILED',
            ]);

            throw new \Exception("Une erreur est survenue lors de la génération du document.");
        }
    }

    private function resolveFieldValue($field, array $inputs, User $user)
    {
        return match ($field->field_type) {

            'INPUT',
            'DATE',
            'NUMBER',
            'SELECT'
            => $inputs[$field->field_key] ?? '',

            'AUTO'
            => $this->autoValue($field, $user),

            'SYSTEM'
            => $this->systemValue($field, $user),

            default => '',
        };
    }

    private function autoValue($field, User $user)
    {
        // Check if the relationship exists before accessing it
        if (!$user) {
            return '';
        }

        $model = match ($field->source_table) {

            'users' => $user,

            'employees' => $user->employee ?? null,

            default => null,
        };

        if (!$model) {
            return '';
        }

        return data_get(
            $model,
            $field->source_field,
            ''
        );
    }

    private function systemValue($field, User $user)
    {
        $config = $field->config_json ?? [];

        return match ($config['generator'] ?? '') {

            'fullname'
            => strtoupper($user->last_name ?? '')
            . ' '
            . ($user->first_name ?? ''),

            'title'
            => ($user->gender === 'M') 
            ? 'Monsieur'
            : (($user->gender === 'F') ? 'Madame' : ''),

            'current_date'
            => now()->format(
                $config['format'] ?? 'd/m/Y'
            ),

            'reference_number'
            => $this->generateReferenceNumber(),

            default => '',
        };
    }

    private function generateReferenceNumber(): string
    {
        return 'REF-' . strtoupper(uniqid());
    }

    private function nextVersion(User $user, DocumentType $document): int
    {
        return GeneratedDocument::where(
            'user_id',
            $user->id
        )
            ->where(
                'document_type_id',
                $document->id
            )
            ->max('version') + 1;
    }
}
        