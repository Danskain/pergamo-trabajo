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
        Schema::create('accounting_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedBigInteger('account_class_id');
            $table->foreign('account_class_id')
                  ->references('id')
                  ->on('account_class')
                  ->onDelete('restrict');
            $table->string('name');
            $table->text('description');
            $table->bigInteger('account_from');
            $table->bigInteger('account_to');
            $table->boolean('affects_closing');
            $table->boolean('affects_financial_statements');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_groups');
    }
};
