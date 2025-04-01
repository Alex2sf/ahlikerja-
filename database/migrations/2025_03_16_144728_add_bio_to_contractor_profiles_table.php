<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBioToContractorProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('admin_note'); // Tambahkan kolom bio setelah media_sosial
        });
    }

    public function down()
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            $table->dropColumn('bio');
        });
    }
}
