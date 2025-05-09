<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Ubah kolom 'periode' menjadi CHAR(7) agar format YYYY-MM pas
            $table->char('periode', 7)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Kembalikan ke bentuk sebelumnya, misal string atau date tergantung sebelumnya
            $table->string('periode')->change(); // sesuaikan dengan kondisi awal jika perlu
        });
    }
};
