<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_server');
            $table->string('lokasi_server');
            $table->text('alamat_server')->nullable();

            // Informasi teknis opsional:
            $table->string('ip_address')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('status')->default('aktif'); // aktif / nonaktif
            $table->string('jenis_server')->nullable(); // e.g. database, file, proxy
            $table->text('keterangan')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
