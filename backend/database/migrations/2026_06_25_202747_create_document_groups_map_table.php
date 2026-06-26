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
        Schema::create('document_groups_map', function (Blueprint $table) {

            $table->foreignId('document_group_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('document_type_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();


            $table->primary(['document_group_id', 'document_type_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_groups_map');
    }
};
