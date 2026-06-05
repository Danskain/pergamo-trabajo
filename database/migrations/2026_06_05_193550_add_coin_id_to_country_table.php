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
        Schema::table('country', function (Blueprint $table) {
            $table->foreignId('coin_id')
                ->nullable()
                ->after('coin')
                ->constrained('coins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country', function (Blueprint $table) {
            $table->dropForeign(['coin_id']);
            $table->dropColumn('coin_id');
        });
    }
};
