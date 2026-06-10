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
        Schema::create('chart_accounts', function (Blueprint $table) {

            $table->id();
            $table->string('code');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('accounting_standard_id');
            $table->foreign('accounting_standard_id')
                  ->references('id')
                  ->on('accounting_standard')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('types_plan_id');
            $table->foreign('types_plan_id')
                  ->references('id')
                  ->on('types_plans')
                  ->onDelete('restrict');

            $table->boolean('ceco_permission');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_accounts');
    }
};
