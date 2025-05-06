<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('olt_ports', function (Blueprint $table) {
$table->uuid('id')->primary();
            $table->string('nama_port')->nullable();
            $table->uuid('olt_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->decimal('losses', 5, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
$table->foreign('olt_id')->references('id')->on('olts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('olt_ports');
    }
};
