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

        $directory = storage_path('app/generated');

        if (!is_dir($directory)) {
            // Fix #1: restrictive permissions + explicit failure
            if (!mkdir($directory, 0750, true) && !is_dir($directory)) {
                throw new \RuntimeException("Failed to create generated documents directory.");
            }
        }

        // Fix #2: collision-resistant filename
        $filename = str($document->template_name)
            ->slug('_')
            . '_'
            . $user->id
            . '_'
            . now()->format('YmdHisv')
            . '_'
            . bin2hex(random_bytes(8))
            . '.docx';

        $path = $directory . '/' . $filename;

        DB::beginTransaction();

        try {
            // Fix #3: template check + TemplateProcessor inside try/catch
            $template = storage_path('app/' . $document->template_path);

            if (!file_exists($template)) {
                throw new \Exception("Template file not found: {$template}");
            }

            $processor = new TemplateProcessor($template);

            foreach ($document->fields as $field) {
                $value = $this->resolveFieldValue($field, $inputs, $user);
                $processor->setValue($field->field_key, $value);
            }

            $processor->saveAs($path);

            if (!file_exists($path)) {
                throw new \Exception("Failed to save document to: {$path}");
            }

            // Fix #4: atomic version with lock
            $version = $this->nextVersion($user, $document);

            GeneratedDocument::create([
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'generated_name' => $filename,
                'generated_path' => "generated/$filename",
                'version' => $version,
                'status' => 'SUCCESS',
            ]);

            DB::commit();

            return $path;

        } catch (\Throwable $e) {
            DB::rollBack();

            // Fix #5: delete file if it was written before the failure
            if (file_exists($path)) {
                @unlink($path);
            }

            \Log::error('Document generation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'document_type_id' => $document->id,
                'exception' => $e,
            ]);

            // Fix #6: FAILED record has no generated_path (nothing to download)
            DB::transaction(function () use ($user, $document, $filename) {
                GeneratedDocument::create([
                    'user_id' => $user->id,
                    'document_type_id' => $document->id,
                    'generated_name' => $filename,
                    'generated_path' => null,
                    'version' => $this->nextVersion($user, $document),
                    'status' => 'FAILED',
                ]);
            });

            throw new \Exception("Une erreur est survenue lors de la génération du document.");
        }
    }

    private function resolveFieldValue($field, array $inputs, User $user)
    {
        return match ($field->field_type) {
            'INPUT', 'DATE', 'NUMBER', 'SELECT' => $inputs[$field->field_key] ?? '',
            'AUTO' => $this->autoValue($field, $user),
            'SYSTEM' => $this->systemValue($field, $user),
            default => '',
        };
    }

    private function autoValue($field, User $user)
    {
        $model = match ($field->source_table) {
            'users' => $user,
            'employees' => $user->employee ?? null,
            default => null,
        };

        if (!$model)
            return '';

        $value = data_get($model, $field->source_field, '');

        // format dates
        if ($value instanceof \Carbon\Carbon || $value instanceof \DateTime) {
            return $value->format('d/m/Y');
        }

        return $value;
    }

    private function systemValue($field, User $user)
    {
        $config = is_array($field->config_json)
            ? $field->config_json
            : json_decode($field->config_json ?? '{}', true) ?? [];

        return match ($config['generator'] ?? '') {
            'fullname' => strtoupper($user->last_name ?? '') . ' ' . ($user->first_name ?? ''),
            'title' => $user->gender === 'M' ? 'Monsieur' : ($user->gender === 'F' ? 'Madame' : ''),
            'current_date' => now()->format($config['format'] ?? 'd/m/Y'),
            'reference_number' => $this->generateReferenceNumber(),
            default => '',
        };
    }

    private function generateReferenceNumber(): string
    {
        return 'REF-' . strtoupper(uniqid());
    }

    private function nextVersion(User $user, DocumentType $document): int
    {
        if (!DB::transactionLevel()) {
            throw new \LogicException('nextVersion() must be called inside a transaction.');
        }

        DB::table('users')
            ->where('id', $user->id)  // was ->whereKey($user->id)
            ->lockForUpdate()
            ->first();

        return ((int) GeneratedDocument::where('user_id', $user->id)
            ->where('document_type_id', $document->id)
            ->max('version')) + 1;
    }
}