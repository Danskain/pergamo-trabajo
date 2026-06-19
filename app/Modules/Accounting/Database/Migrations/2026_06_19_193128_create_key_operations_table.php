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
        Schema::create('key_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->foreignId('module_id')
                  ->constrained('modules')
                  ->onDelete('cascade');
            $table->foreignId('accounting_nature_id')
                  ->constrained('accounting_nature')
                  ->onDelete('cascade');
            $table->boolean('affects_taxes')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_operations');
    }
};
