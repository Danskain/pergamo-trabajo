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
        Schema::create('country', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('sga_origin_fk')->nullable();
            $table->integer('code')->nullable();
            $table->integer('code2')->nullable();
            $table->integer('cod_iso')->nullable();
            $table->string('cod_iso_alfa2', 5)->nullable();
            $table->string('cod_iso_alfa3', 5)->nullable();
            $table->integer('indicative')->nullable();
            $table->string('coin', 25)->nullable();
            $table->unsignedTinyInteger('status_id')->default(1)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country');
    }
};
