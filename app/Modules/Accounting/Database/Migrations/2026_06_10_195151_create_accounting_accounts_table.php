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
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->unsignedBigInteger('chart_account_id');
            $table->foreign('chart_account_id')->references('id')->on('chart_accounts')->onDelete('restrict');
            $table->string('name');
            $table->unsignedBigInteger('account_class_id');
            $table->foreign('account_class_id')->references('id')->on('account_class')->onDelete('restrict');
            $table->unsignedBigInteger('types_account_id');
            $table->foreign('types_account_id')->references('id')->on('types_accounts')->onDelete('restrict');
            $table->unsignedBigInteger('accounting_group_id');
            $table->foreign('accounting_group_id')->references('id')->on('accounting_groups')->onDelete('restrict');
            $table->boolean('allows_manual_transactions');
            $table->boolean('associated_account');
            $table->boolean('accepts_taxes');
            $table->boolean('foreign_currency');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_accounts');
    }
};
