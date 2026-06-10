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
        Schema::create('business_structure', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('country')->onDelete('restrict');
            
            $table->unsignedBigInteger('coin_id');
            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('restrict');
            
            $table->unsignedBigInteger('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprises')->onDelete('restrict');
            
            $table->unsignedBigInteger('exercise_variation_id');
            $table->foreign('exercise_variation_id')->references('id')->on('exercise_variations')->onDelete('restrict');
            
            $table->unsignedBigInteger('chart_account_id');
            $table->foreign('chart_account_id')->references('id')->on('chart_accounts')->onDelete('restrict');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_structure');
    }
};
