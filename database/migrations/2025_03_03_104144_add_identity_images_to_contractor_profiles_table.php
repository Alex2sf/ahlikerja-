<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->json('identity_images')->nullable()->after('portofolio'); // Kolom JSON untuk multiple images
        });
    }

    public function down()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->dropColumn('identity_images');
        });
    }
};
