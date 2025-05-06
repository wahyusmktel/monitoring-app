<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odcs', function (Blueprint $table) {
$table->uuid('id')->primary();
            $table->string('nama_odc')->nullable();
            $table->uuid('otb_id')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->decimal('losses', 5, 2)->nullable();
            $table->text('lokasi')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
            $table->softDeletes();
$table->foreign('otb_id')->references('id')->on('otbs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odcs');
    }
};
