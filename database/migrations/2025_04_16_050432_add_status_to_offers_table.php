<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOffersTable extends Migration
{
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('accepted'); // Kolom status dengan default 'pending'
        });

        // Update data yang sudah ada
        \App\Models\Offer::where('accepted', true)->update(['status' => 'accepted']);
        \App\Models\Offer::where('accepted', false)->update(['status' => 'pending']);
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
