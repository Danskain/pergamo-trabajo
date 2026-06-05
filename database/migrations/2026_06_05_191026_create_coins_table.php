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
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alphabetic_code', 10);
            $table->unsignedInteger('numeric_code');
            $table->string('subunit');
            $table->unsignedInteger('subunit_ratio');
            $table->unsignedTinyInteger('decimal_digits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coins');
    }
};
