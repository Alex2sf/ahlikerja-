<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStagesToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('payment_stage')->default(0)->after('is_completed'); // 0: belum mulai, 1-4: tahap pembayaran
            $table->string('payment_proof_1')->nullable()->after('payment_stage');
            $table->string('payment_proof_2')->nullable()->after('payment_proof_1');
            $table->string('payment_proof_3')->nullable()->after('payment_proof_2');
            $table->string('payment_proof_4')->nullable()->after('payment_proof_3');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_stage', 'payment_proof_1', 'payment_proof_2', 'payment_proof_3', 'payment_proof_4']);
        });
    }
}
