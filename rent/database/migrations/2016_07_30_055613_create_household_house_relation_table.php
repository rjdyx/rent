<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseholdHouseRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('household_house_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(1);//1：单人拥有，2：双人合租，3：合租后退房，4：单人拥有后退房
            $table->integer('household_id');//住户id
            $table->integer('household_house_id');//租房id
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
        //
    }
}
