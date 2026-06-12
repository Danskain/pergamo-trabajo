<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_source_type', function (Blueprint $table) {
           // ID auto-incrementable (big integer)
            $table->id();
            
            // Campos básicos
            $table->string('name');
            $table->string('code');
            $table->text('description'); // Texto grande
            
            // Booleanos
            $table->boolean('generates_accounting');
            $table->boolean('manual_entry');
            $table->boolean('requires_approval');
            $table->boolean('requires_third');
            $table->boolean('requires_ceco');
            $table->boolean('affects_inventory');
            $table->boolean('affects_cartera');
            $table->boolean('affects_cxp');
            $table->boolean('affects_treasury');
            $table->boolean('allows_reversal');
            
            // Soft deletes (agrega columna deleted_at)
            $table->softDeletes();
            
            // Timestamps (created_at, updated_at) - recomendado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_source_type');
    }
};
