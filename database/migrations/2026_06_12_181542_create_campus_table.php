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
        Schema::create('campus', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);

            $table->string('address', 255)->nullable();
            $table->string('enable_code', 255)->nullable();

            $table->unsignedTinyInteger('status_id')->nullable();

            $table->unsignedBigInteger('billing_pad_prefix_id')->nullable();
            $table->unsignedBigInteger('billing_pad_credit_note_prefix_id')->nullable();
            $table->unsignedBigInteger('billing_pad_debit_note_prefix_id')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('municipality_id')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Índices observados en la imagen
            $table->index('name');
            $table->index('status_id');
            $table->index('billing_pad_prefix_id');
            $table->index('billing_pad_credit_note_prefix_id');
            $table->index('billing_pad_debit_note_prefix_id');
            $table->index('region_id');
            $table->index('municipality_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus');
    }
};
