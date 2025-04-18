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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->json('gambar')->nullable(); // Menyimpan path gambar (multiple files)
            $table->string('lokasi');
            $table->decimal('estimasi_anggaran', 15, 2); // Untuk menyimpan angka desimal
            $table->string('durasi'); // Misalnya: "2 minggu", "1 bulan", dll.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
