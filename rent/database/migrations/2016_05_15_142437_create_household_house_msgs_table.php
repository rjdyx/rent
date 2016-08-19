<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseholdHouseMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('household_house_msg', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region_id');//区域
            $table->integer('address_id');//房址
            $table->string('room_number')->nullable();//房间号
            $table->decimal('area', 11, 2);//租房面积
            $table->timestamp('firsttime_check_in')->nullable();//第一次入住时间
            $table->timestamp('lasttime_pay_rent')->nullable();//上次交租时间
            $table->integer('is_check_out')->default(0);//是否退房：0：否，1：是
            $table->integer('order')->default(1);//表示这间房是第几间房
            $table->integer('household_id');
            $table->string('remark')->nullable();//备注
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
        Schema::drop('household_house_msg');
    }
}
