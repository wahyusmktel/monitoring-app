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
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->decimal('latitude', 18, 15)->nullable()->change();
            $table->decimal('longitude', 18, 15)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->change(); // asumsi semula
            $table->decimal('longitude', 11, 8)->nullable()->change(); // asumsi semula
        });
    }
};
