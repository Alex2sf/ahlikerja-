<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contractor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('foto_profile')->nullable();
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->string('nomor_telepon')->nullable();
            $table->text('alamat')->nullable();
            $table->string('perusahaan');
            $table->string('nomor_npwp');
            $table->json('bidang_usaha')->nullable(); // Menyimpan hingga 10 bidang usaha dalam JSON
            $table->json('dokumen_pendukung')->nullable(); // Menyimpan path dokumen pendukung (multiple files)
            $table->json('portofolio')->nullable(); // Menyimpan path portofolio (multiple files)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_profiles');
    }
};
