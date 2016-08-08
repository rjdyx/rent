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
            $table->string('name',50);//姓名
            $table->string('job_number',12);//工号
            $table->string('card_number',19)->nullable();//银行卡号
            $table->string('institution',20);//单位
            $table->integer('has_house')->default(0);//是否有房：0：无房，1：八区内有房，2：有房改房
            $table->integer('is_dimission')->default(0);//是否离职：0：否，1：是
            $table->integer('has_house_or_subsidy')->default(0);//是否有房改和补贴：0：否，1：是
            $table->string('input_count_time')->nullable();//手动累计时间，格式为年.月.日，如4.2.4
            $table->integer('incre_count_time')->nullable();//自动累计时间，单位天数
            $table->timestamp('time_point')->nullable();//有房时间点
            $table->timestamp('in_school_time')->nullable();//入校时间
            $table->integer('type')->default(0);//发放方式，0：校发，1：省发，2：租赁人员，3：博士后
            $table->integer('privilege')->default(0);//是否享受标租，0：否，1：是
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
