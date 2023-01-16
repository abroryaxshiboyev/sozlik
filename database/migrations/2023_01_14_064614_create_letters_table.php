<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('latin');
            $table->text('kiril');
            $table->timestamps();
        });
        DB::table('letters')->insert(['name' => 'a', 'latin' => 'a','kiril'=>'а']);
        DB::table('letters')->insert(['name' => 'á', 'latin' => 'á','kiril'=>'ә']);
        DB::table('letters')->insert(['name' => 'b', 'latin' => 'b','kiril'=>'б']);
        DB::table('letters')->insert(['name' => 'd', 'latin' => 'd','kiril'=>'д']);
        DB::table('letters')->insert(['name' => 'e', 'latin' => 'e','kiril'=>'е']);
        DB::table('letters')->insert(['name' => 'f', 'latin' => 'f','kiril'=>'ф']);
        DB::table('letters')->insert(['name' => 'g', 'latin' => 'g','kiril'=>'г']);
        DB::table('letters')->insert(['name' => 'ǵ', 'latin' => 'ǵ','kiril'=>'ғ']);
        DB::table('letters')->insert(['name' => 'h', 'latin' => 'h','kiril'=>'ҳ']);
        DB::table('letters')->insert(['name' => 'x', 'latin' => 'x','kiril'=>'х']);
        DB::table('letters')->insert(['name' => 'ı', 'latin' => 'ı','kiril'=>'ы']);
        DB::table('letters')->insert(['name' => 'i', 'latin' => 'i','kiril'=>'и']);
        DB::table('letters')->insert(['name' => 'j', 'latin' => 'j','kiril'=>'ж']);
        DB::table('letters')->insert(['name' => 'k', 'latin' => 'k','kiril'=>'к']);
        DB::table('letters')->insert(['name' => 'q', 'latin' => 'q','kiril'=>'қ']);
        DB::table('letters')->insert(['name' => 'l', 'latin' => 'l','kiril'=>'л']);
        DB::table('letters')->insert(['name' => 'm', 'latin' => 'm','kiril'=>'м']);
        DB::table('letters')->insert(['name' => 'n', 'latin' => 'n','kiril'=>'н']);
        DB::table('letters')->insert(['name' => 'ń', 'latin' => 'ń','kiril'=>'ң']);
        DB::table('letters')->insert(['name' => 'o', 'latin' => 'o','kiril'=>'о']);
        DB::table('letters')->insert(['name' => 'ó', 'latin' => 'ó','kiril'=>'ө']);
        DB::table('letters')->insert(['name' => 'p', 'latin' => 'p','kiril'=>'п']);
        DB::table('letters')->insert(['name' => 'r', 'latin' => 'r','kiril'=>'р']);
        DB::table('letters')->insert(['name' => 's', 'latin' => 's','kiril'=>'с']);
        DB::table('letters')->insert(['name' => 't', 'latin' => 't','kiril'=>'т']);
        DB::table('letters')->insert(['name' => 'u', 'latin' => 'u','kiril'=>'у']);
        DB::table('letters')->insert(['name' => 'ú', 'latin' => 'ú','kiril'=>'ү']);
        DB::table('letters')->insert(['name' => 'v', 'latin' => 'v','kiril'=>'в']);
        DB::table('letters')->insert(['name' => 'w', 'latin' => 'w','kiril'=>'у']);
        DB::table('letters')->insert(['name' => 'y', 'latin' => 'y','kiril'=>'й']);
        DB::table('letters')->insert(['name' => 'z', 'latin' => 'z','kiril'=>'з']);
        DB::table('letters')->insert(['name' => 'c', 'latin' => 'c','kiril'=>'ц']);
        DB::table('letters')->insert(['name' => 'sh', 'latin' => 'sh','kiril'=>'ш']);
        DB::table('letters')->insert(['name' => 'ch', 'latin' => 'ch','kiril'=>'ч']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letters');
    }
}
