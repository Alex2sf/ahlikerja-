<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Ubah order_id menjadi nullable
            $table->foreignId('order_id')->nullable()->change();
            // Pastikan booking_id juga nullable (jika belum)
            $table->foreignId('booking_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Kembalikan ke NOT NULL (opsional, tergantung kebutuhan)
            $table->foreignId('order_id')->nullable(false)->change();
            $table->foreignId('booking_id')->nullable(false)->change();
        });
    }
};
