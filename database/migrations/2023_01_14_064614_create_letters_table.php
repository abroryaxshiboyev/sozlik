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
            $table->text('latin');
            $table->text('kiril');
            $table->timestamps();
        });
        DB::table('letters')->insert(['latin' => 'a','kiril'=>'а']);
        DB::table('letters')->insert(['latin' => 'á','kiril'=>'ә']);
        DB::table('letters')->insert(['latin' => 'b','kiril'=>'б']);
        DB::table('letters')->insert(['latin' => 'd','kiril'=>'д']);
        DB::table('letters')->insert(['latin' => 'e','kiril'=>'е']);
        DB::table('letters')->insert(['latin' => 'f','kiril'=>'ф']);
        DB::table('letters')->insert(['latin' => 'g','kiril'=>'г']);
        DB::table('letters')->insert(['latin' => 'ǵ','kiril'=>'ғ']);
        DB::table('letters')->insert(['latin' => 'h','kiril'=>'ҳ']);
        DB::table('letters')->insert(['latin' => 'x','kiril'=>'х']);
        DB::table('letters')->insert(['latin' => 'ı','kiril'=>'ы']);
        DB::table('letters')->insert(['latin' => 'i','kiril'=>'и']);
        DB::table('letters')->insert(['latin' => 'j','kiril'=>'ж']);
        DB::table('letters')->insert(['latin' => 'k','kiril'=>'к']);
        DB::table('letters')->insert(['latin' => 'q','kiril'=>'қ']);
        DB::table('letters')->insert(['latin' => 'l','kiril'=>'л']);
        DB::table('letters')->insert(['latin' => 'm','kiril'=>'м']);
        DB::table('letters')->insert(['latin' => 'n','kiril'=>'н']);
        DB::table('letters')->insert(['latin' => 'ń','kiril'=>'ң']);
        DB::table('letters')->insert(['latin' => 'o','kiril'=>'о']);
        DB::table('letters')->insert(['latin' => 'ó','kiril'=>'ө']);
        DB::table('letters')->insert(['latin' => 'p','kiril'=>'п']);
        DB::table('letters')->insert(['latin' => 'r','kiril'=>'р']);
        DB::table('letters')->insert(['latin' => 's','kiril'=>'с']);
        DB::table('letters')->insert(['latin' => 't','kiril'=>'т']);
        DB::table('letters')->insert(['latin' => 'u','kiril'=>'у']);
        DB::table('letters')->insert(['latin' => 'ú','kiril'=>'ү']);
        DB::table('letters')->insert(['latin' => 'v','kiril'=>'в']);
        DB::table('letters')->insert(['latin' => 'w','kiril'=>'у']);
        DB::table('letters')->insert(['latin' => 'y','kiril'=>'й']);
        DB::table('letters')->insert(['latin' => 'z','kiril'=>'з']);
        DB::table('letters')->insert(['latin' => 'c','kiril'=>'ц']);
        DB::table('letters')->insert(['latin' => 'sh','kiril'=>'ш']);
        DB::table('letters')->insert(['latin' => 'ch','kiril'=>'ч']);
        
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
