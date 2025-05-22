<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->json('legalitas')->nullable()->after('identity_images'); // Kolom untuk menyimpan array path dokumen legalitas
        });
    }

    public function down()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->dropColumn('legalitas');
        });
    }
};
