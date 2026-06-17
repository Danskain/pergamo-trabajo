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
        Schema::create('accounting_entry_position', function (Blueprint $table) {
            // ID auto incrementable
            $table->id();
            
            // Relaciones principales
            $table->foreignId('business_structure_id')
                  ->constrained('business_structure')
                  ->onDelete('restrict');
                  
            $table->foreignId('accounting_document_id')
                  ->constrained('accounting_document')
                  ->onDelete('restrict');
                  
            $table->foreignId('accounting_entry_header_id')
                  ->constrained('accounting_entry_header')
                  ->onDelete('restrict');
                  
            $table->foreignId('accounting_accounts_id')
                  ->constrained('accounting_accounts')
                  ->onDelete('restrict');
            
            // ID del tercero (numérico)
            $table->unsignedBigInteger('id_tercero')->nullable();
            
            // Indicador DC (enum: Debito, Credito)
            $table->enum('indicator_dc', ['Debito', 'Credito'])->nullable();
            
            // Monto (decimal)
            $table->decimal('amount', 15, 2)->default(0);
            
            // Relación con moneda
            $table->foreignId('coin_id')
                  ->constrained('coins')
                  ->onDelete('restrict');
            
            // Relación con centro de costo
            $table->foreignId('cost_center_id')
                  ->nullable()
                  ->constrained('cost_center')
                  ->onDelete('set null');
            
            // Texto de posición
            $table->string('position_text', 255)->nullable();
            
            // SoftDelete
            $table->softDeletes();
            
            // Timestamps adicionales
            $table->timestamps();
            
            // Índices recomendados para mejorar rendimiento
            $table->index(['business_structure_id']);
            $table->index(['accounting_document_id']);
            $table->index(['accounting_entry_header_id']);
            $table->index(['accounting_accounts_id']);
            $table->index(['coin_id']);
            $table->index(['cost_center_id']);
            $table->index(['id_tercero']);
            $table->index(['indicator_dc']);
            $table->index(['deleted_at']);
            
            // Índices compuestos opcionales (descomenta si los necesitas)
            // $table->index(['accounting_entry_header_id', 'accounting_accounts_id']);
            // $table->index(['business_structure_id', 'indicator_dc']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_entry_position');
    }
};
