<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
$table->uuid('id')->primary();
            $table->string('nama_pelanggan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->uuid('odp_id')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
            $table->softDeletes();
$table->foreign('odp_id')->references('id')->on('odps')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
