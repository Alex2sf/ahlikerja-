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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade'); // Kontraktor yang menawarkan
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Postingan yang ditawarkan
            $table->boolean('accepted')->default(false); // Status apakah penawaran diterima oleh user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
