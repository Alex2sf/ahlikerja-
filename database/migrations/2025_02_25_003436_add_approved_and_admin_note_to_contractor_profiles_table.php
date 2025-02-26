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
            $table->boolean('approved')->default(false)->after('portofolio'); // Default false (menunggu persetujuan)
            $table->text('admin_note')->nullable()->after('approved'); // Catatan dari admin (opsional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contractor_profiles', function (Blueprint $table) {
            //
        });
    }
};
