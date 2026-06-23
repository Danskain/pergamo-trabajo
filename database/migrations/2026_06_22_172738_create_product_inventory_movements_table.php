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
        Schema::create('product_inventory_movements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('concept_id');

            $table->boolean('control_book')->default(false);

            $table->date('date');

            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('pharmacy_stock_id')->nullable();

            $table->string('prescription_number')->nullable(); // Número de receta médica

            $table->integer('quantity'); // Cantidad del movimiento

            $table->string('record_number')->nullable(); // Número de acta

            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('pharmacy_product_request_id')->nullable();

            $table->timestamps();

            $table->unsignedBigInteger('inventory_count_id')->nullable();

            $table->unsignedBigInteger('movement_dispatch_id')->nullable();

            $table->unsignedBigInteger('request_inventory_id')->nullable();

            // Índices
            /* $table->index('batch_id');
            $table->index('concept_id');
            $table->index('invoice_id');
            $table->index('pharmacy_stock_id');
            $table->index('user_id');
            $table->index('inventory_count_id');
            $table->index('movement_dispatch_id');
            $table->index('request_inventory_id') */;

            // Foreign Keys (ajusta los nombres de tablas según tu proyecto)
            /*
            $table->foreign('batch_id')
                ->references('id')
                ->on('batches');

            $table->foreign('concept_id')
                ->references('id')
                ->on('concepts');

            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices');

            $table->foreign('pharmacy_stock_id')
                ->references('id')
                ->on('pharmacy_stocks');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('pharmacy_product_request_id')
                ->references('id')
                ->on('pharmacy_product_requests');

            $table->foreign('inventory_count_id')
                ->references('id')
                ->on('inventory_counts');

            $table->foreign('movement_dispatch_id')
                ->references('id')
                ->on('movement_dispatches');

            $table->foreign('request_inventory_id')
                ->references('id')
                ->on('request_inventories');
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_movements');
    }
};
