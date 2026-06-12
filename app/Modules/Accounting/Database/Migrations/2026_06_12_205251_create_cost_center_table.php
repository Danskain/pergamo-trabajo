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
        Schema::create('cost_center', function (Blueprint $table) {
             // ID auto-incrementable grande
            $table->id();

            // Relación con business_structure
            $table->unsignedBigInteger('business_structure_id');
            $table->foreign('business_structure_id')
                  ->references('id')
                  ->on('business_structure')
                  ->onDelete('restrict');

            // Relación con campus
            $table->unsignedBigInteger('campus_id');
            $table->foreign('campus_id')
                  ->references('id')
                  ->on('campus')
                  ->onDelete('restrict');

            // Campos básicos
            $table->string('code');
            $table->string('name');
            $table->text('description');

            // Relación con cost_center_type
            $table->unsignedBigInteger('cost_center_type_id');
            $table->foreign('cost_center_type_id')
                  ->references('id')
                  ->on('cost_center_type')
                  ->onDelete('restrict');

            // Relación con cost_center_class
            $table->unsignedBigInteger('cost_center_class_id');
            $table->foreign('cost_center_class_id')
                  ->references('id')
                  ->on('cost_center_class')
                  ->onDelete('restrict');

            // Relación con cost_center_nature
            $table->unsignedBigInteger('cost_center_nature_id');
            $table->foreign('cost_center_nature_id')
                  ->references('id')
                  ->on('cost_center_nature')
                  ->onDelete('restrict');

            // Booleanos
            $table->boolean('allows_allocation');
            $table->boolean('distributes_costs');
            $table->boolean('functional_unit');
            $table->boolean('profit_center');

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
        Schema::dropIfExists('cost_center');
    }
};
