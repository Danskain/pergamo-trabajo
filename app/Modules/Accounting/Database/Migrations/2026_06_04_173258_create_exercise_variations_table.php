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
        Schema::create('exercise_variations', function (Blueprint $table) {
            $table->id();                              // id autoincrementable
            $table->string('name')->nullable();        // varchar nullable
            $table->unsignedBigInteger('start_exercise'); // FK a months.id
            $table->unsignedBigInteger('end_exercise');   // FK a months.id
            $table->integer('normal_periods');         // número entero
            $table->integer('special_periods');        // número entero (cambié a snake_case)
            $table->boolean('calendar_dependent');     // boolean
            $table->longText('description')->nullable(); // longtext nullable
            $table->softDeletes();                     // soft delete (deleted_at)
            $table->timestamps();                      // created_at y updated_at (opcional pero recomendado)

            // Definir las claves foráneas
            $table->foreign('start_exercise')
                  ->references('id')
                  ->on('months')
                  ->onDelete('restrict'); // o cascade según necesites

            $table->foreign('end_exercise')
                  ->references('id')
                  ->on('months')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_variations');
    }
};
