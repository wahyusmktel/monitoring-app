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
        Schema::table('odps', function (Blueprint $table) {
            $table->integer('jumlah_port')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('odps', function (Blueprint $table) {
            $table->dropColumn('jumlah_port');
        });
    }
};
