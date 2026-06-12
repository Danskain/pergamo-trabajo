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
        Schema::create('accounting_entry_header', function (Blueprint $table) {
             // ID auto-incrementable grande
            $table->id();
            
            // Relación con business_structure
            $table->unsignedBigInteger('business_structure_id');
            $table->foreign('business_structure_id')
                  ->references('id')
                  ->on('business_structure')
                  ->onDelete('restrict');
            
            // Relación con accounting_document
            $table->unsignedBigInteger('accounting_document_id');
            $table->foreign('accounting_document_id')
                  ->references('id')
                  ->on('accounting_document')
                  ->onDelete('restrict');
            
            // Período contable (numérico, ej: 202512)
            $table->integer('accounting_period');
            
            // Relación con coins
            $table->unsignedBigInteger('coin_id');
            $table->foreign('coin_id')
                  ->references('id')
                  ->on('coins')
                  ->onDelete('restrict');
            
            // Descripción texto grande
            $table->text('description');
            
            // Totales débitos y créditos (decimal con 2 decimales)
            $table->decimal('total_debits', 15, 2);
            $table->decimal('total_credits', 15, 2);
            
            // Documento de referencia
            $table->string('reference_document');
            
            // Relación con documents_source
            $table->unsignedBigInteger('documents_source_id');
            $table->foreign('documents_source_id')
                  ->references('id')
                  ->on('documents_source')
                  ->onDelete('restrict');
            
            // Soft deletes
            $table->softDeletes();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_entry_header');
    }
};
