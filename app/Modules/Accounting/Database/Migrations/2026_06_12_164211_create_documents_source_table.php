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
        Schema::create('documents_source', function (Blueprint $table) {
            // ID auto-incrementable grande
            $table->id();
            
            // Relaciones (claves foráneas)
            $table->unsignedBigInteger('business_structure_id');
            $table->foreign('business_structure_id')
                  ->references('id')
                  ->on('business_structure')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('modules_id');
            $table->foreign('modules_id')
                  ->references('id')
                  ->on('modules')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('document_source_type_id');
            $table->foreign('document_source_type_id')
                  ->references('id')
                  ->on('document_source_type')
                  ->onDelete('restrict');
            
            // Campos básicos
            $table->string('number_document_source');
            $table->dateTime('document_date');   // DATETIME
            $table->dateTime('accounting_date'); // DATETIME
            
            // Relación con tabla reference
            $table->unsignedBigInteger('reference_id');
            $table->foreign('reference_id')
                  ->references('id')
                  ->on('reference')
                  ->onDelete('restrict');
            
            // Valor total decimal (10,2) como ejemplo, ajusta según necesidades
            $table->decimal('total_value', 15, 2); // 15 dígitos totales, 2 decimales
            
            // Relación con tabla coins
            $table->unsignedBigInteger('coin_id');
            $table->foreign('coin_id')
                  ->references('id')
                  ->on('coins')
                  ->onDelete('restrict');
            
            // Relación con tabla financial_statements
            $table->unsignedBigInteger('financial_statement_id');
            $table->foreign('financial_statement_id')
                  ->references('id')
                  ->on('financial_statements')
                  ->onDelete('restrict');
            
            // Relación con tabla accounting_document
            $table->unsignedBigInteger('accounting_document_id');
            $table->foreign('accounting_document_id')
                  ->references('id')
                  ->on('accounting_document')
                  ->onDelete('restrict');
            
            // Ejercicio (numérico)
            $table->integer('exercise'); // o $table->year('exercise') si es un año
            
            // Descripción texto grande
            $table->text('description');
            
            // Soft deletes
            $table->softDeletes();
            
            // Timestamps (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_source');
    }
};
