<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseholdMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('household_msg', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',10);//姓名
            $table->string('job_number',12);//工号
            $table->string('card_number',19);//银行卡号
            $table->string('institution',20);//单位
            $table->integer('has_house')->default(0);//是否有房：0：无房，1：有商品房，2：有房改房
            $table->timestamp('has_house_time')->nullable();//有房时间
            $table->timestamp('has_not_house_time')->nullable();//无房时间
            $table->integer('is_dimission')->default(0);//是否离职：0：否，1：是
            $table->timestamp('dimission_time')->nullable();//离职时间
            $table->integer('type')->default(0);//发放方式，0：校发，1：省发，2：租赁人员，3：博士后
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
        Schema::drop('household_msg');
    }
}
