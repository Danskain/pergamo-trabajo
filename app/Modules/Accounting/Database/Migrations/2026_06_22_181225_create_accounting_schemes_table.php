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
        Schema::create('accounting_schemes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_structure_id')
                ->constrained('business_structure');

            $table->foreignId('chart_account_id')
                ->constrained('chart_accounts');

            $table->string('assessment_class');

            $table->foreignId('type_movement_id')
                ->constrained('product_inventory_movements');

            $table->foreignId('accounting_event_id')
                ->constrained('accounting_events');

            $table->foreignId('key_operation_id')
                ->constrained('key_operations');

            $table->foreignId('accounting_account_id')
                ->constrained('accounting_accounts');

            $table->foreignId('accounting_nature_id')
                ->constrained('accounting_nature');

            $table->boolean('require_coce')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_schemes');
    }
};
