<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('odp_ports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('odp_id');
            $table->integer('port_number'); // Port ke-1, ke-2, dst
            $table->uuid('pelanggan_id')->nullable();
            $table->enum('status', ['kosong', 'terpakai'])->default('kosong');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('odp_id')->references('id')->on('odps')->onDelete('cascade');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggans')->onDelete('set null');

            $table->unique(['odp_id', 'port_number']); // port unik per ODP
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odp_ports');
    }
};
