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
        Schema::create('business_group', function (Blueprint $table) {
            $table->id();

            $table->integer('code')
                ->comment('Código del grupo empresarial');

            $table->string('name', 25)
                ->comment('Nombre del grupo empresarial');

            $table->unsignedTinyInteger('status_id')
                ->default(1)
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_group');
    }
};
