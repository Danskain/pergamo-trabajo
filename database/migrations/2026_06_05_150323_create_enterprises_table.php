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
        Schema::create('enterprises', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->nullable();
            $table->unsignedBigInteger('nit')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->tinyInteger('code_verification')->nullable();
            $table->string('phone_enterprises', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('date')->nullable();
            $table->unsignedTinyInteger('status')->nullable()->index();

            // Clave foránea correcta (solo una vez)
            $table->foreignId('business_group_id')
                ->nullable()
                ->constrained('business_group')
                ->index(); // índice opcional

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enterprises');
    }
};
