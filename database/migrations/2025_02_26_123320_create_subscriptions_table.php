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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade'); // Kontraktor yang berlangganan
            $table->dateTime('start_date'); // Tanggal mulai berlangganan
            $table->dateTime('end_date')->nullable(); // Tanggal berakhir berlangganan (null jika aktif)
            $table->string('transaction_id')->nullable(); // ID transaksi Midtrans (untuk verifikasi)
            $table->boolean('is_active')->default(false); // Status aktif atau tidak
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
