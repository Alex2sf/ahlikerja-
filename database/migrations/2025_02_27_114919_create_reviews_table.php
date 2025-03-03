<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Relasi ke orders
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pengguna yang memberi ulasan
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade'); // Kontraktor yang diulas
            $table->integer('rating')->unsigned()->check('rating >= 1 AND rating <= 5'); // Rating 1-5
            $table->text('review')->nullable(); // Ulasan teks
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
