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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang memesan
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade'); // Kontraktor yang diterima
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Postingan terkait
            $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade'); // Tawaran yang diterima
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
