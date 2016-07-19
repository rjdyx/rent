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
            $table->integer('household_id');//租户id
            $table->integer('household_house_id');//租房id
            $table->integer('intervel');//天数
            $table->integer('isDimission');//是否离职
            $table->integer('order');//第几间租房
            $table->integer('hasHouse');//是否有房
            $table->integer('time');//年限
            $table->string('region',20);//区域
            $table->string('address',20);//房址
            $table->integer('room_number');//房间号
            $table->decimal('money', 11, 2);//租金x比例
            $table->decimal('area', 11, 2);//租房面积
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
