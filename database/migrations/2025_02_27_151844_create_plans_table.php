<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id(); // Kolom `id` sebagai primary key
            $table->string('name'); // Nama plan, misalnya "Basic", "Premium", dll
            $table->decimal('price', 8, 2); // Harga plan (contoh Rp1)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
