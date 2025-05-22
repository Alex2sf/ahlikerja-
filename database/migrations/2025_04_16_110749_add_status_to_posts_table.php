<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('status')->default('open')->after('durasi'); // Kolom status dengan default 'open'
        });

        // Update data yang sudah ada berdasarkan penawaran yang diterima
        $posts = \App\Models\Post::all();
        foreach ($posts as $post) {
            if ($post->offers()->where('status', 'accepted')->exists()) {
                $post->update(['status' => 'closed']);
            }
        }
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
