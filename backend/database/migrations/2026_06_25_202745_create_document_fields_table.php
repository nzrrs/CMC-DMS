<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('field_key');
            $table->enum('field_type',['AUTO','INPUT','SELECT','DATE','NUMBER','SYSTEM']);
            $table->string('source_table')->nullable();
            $table->string('source_field')->nullable();
            $table->boolean('is_required')->default(true);
            $table->json('config_json')->nullable();
            $table->timestamps();

            $table->unique(['document_type_id', 'field_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_fields');
    }
};
