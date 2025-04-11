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
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->dropColumn(['nama_depan', 'nama_belakang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->string('nama_depan')->after('foto_profile');
            $table->string('nama_belakang')->after('nama_depan');
        });
    }
};
