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
            $table->softDeletes();
            $table->timestamps();
        });
        

        DB::table('letters')->insert(['kiril' => 'А', 'latin' => 'A']);
        DB::table('letters')->insert(['kiril' => 'Ә', 'latin' => 'Á']);
        DB::table('letters')->insert(['kiril' => 'Б', 'latin' => 'B']);
        DB::table('letters')->insert(['kiril' => 'Д', 'latin' => 'D']);
        DB::table('letters')->insert(['kiril' => 'Е', 'latin' => 'E']);
        DB::table('letters')->insert(['kiril' => 'Ф', 'latin' => 'F']);
        DB::table('letters')->insert(['kiril' => 'Г', 'latin' => 'G']);
        DB::table('letters')->insert(['kiril' => 'Ғ', 'latin' => 'Ǵ']);
        DB::table('letters')->insert(['kiril' => 'Ҳ', 'latin' => 'H']);
        DB::table('letters')->insert(['kiril' => 'Х', 'latin' => 'X']);
        DB::table('letters')->insert(['kiril' => 'Ы', 'latin' => 'Í']);
        DB::table('letters')->insert(['kiril' => 'И', 'latin' => 'I']);
        DB::table('letters')->insert(['kiril' => 'Ж', 'latin' => 'J']);
        DB::table('letters')->insert(['kiril' => 'К', 'latin' => 'K']);
        DB::table('letters')->insert(['kiril' => 'Қ', 'latin' => 'Q']);
        DB::table('letters')->insert(['kiril' => 'Л', 'latin' => 'L']);
        DB::table('letters')->insert(['kiril' => 'М', 'latin' => 'M']);
        DB::table('letters')->insert(['kiril' => 'Н', 'latin' => 'N']);
        DB::table('letters')->insert(['kiril' => 'Ң', 'latin' => 'Ń']);
        DB::table('letters')->insert(['kiril' => 'О', 'latin' => 'O']);
        DB::table('letters')->insert(['kiril' => 'Ө', 'latin' => 'Ó']);
        DB::table('letters')->insert(['kiril' => 'П', 'latin' => 'P']);
        DB::table('letters')->insert(['kiril' => 'Р', 'latin' => 'R']);
        DB::table('letters')->insert(['kiril' => 'С', 'latin' => 'S']);
        DB::table('letters')->insert(['kiril' => 'Т', 'latin' => 'T']);
        DB::table('letters')->insert(['kiril' => 'У', 'latin' => 'U']);
        DB::table('letters')->insert(['kiril' => 'Ў', 'latin' => 'Ú']);
        DB::table('letters')->insert(['kiril' => 'В', 'latin' => 'V']);
        DB::table('letters')->insert(['kiril' => 'У', 'latin' => 'W']);
        DB::table('letters')->insert(['kiril' => 'Й', 'latin' => 'Y']);
        DB::table('letters')->insert(['kiril' => 'З', 'latin' => 'Z']);
        DB::table('letters')->insert(['kiril' => 'Ц', 'latin' => 'C']);
        DB::table('letters')->insert(['kiril' => 'Ш', 'latin' => 'Sh']);
        DB::table('letters')->insert(['kiril' => 'Ч', 'latin' => 'Ch']);
        DB::table('letters')->insert(['kiril' => 'Я', 'latin' => 'Ya']);
        DB::table('letters')->insert(['kiril' => 'Ю', 'latin' => 'Yu']);
        DB::table('letters')->insert(['kiril' => 'Ё', 'latin' => 'Yo']);

    }
    //á ú ń ó ı ǵ
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
