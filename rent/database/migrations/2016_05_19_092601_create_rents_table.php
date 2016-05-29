<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('firsttime_check_in');//入住时间
            $table->timestamp('lasttime_pay_rent')->nullable();//房租上次结算时间
            $table->timestamp('time_pay_rent');//房租结算时间
            $table->decimal('rent', 11, 2);//房租
            $table->string('formulas');//计算公式（天数*[年限+区域+房址+租金]*平方数*比例=房租）
            $table->integer('household_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rents');
    }
}
