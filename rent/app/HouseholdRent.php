<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseholdRent extends Model
{
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'job_number', 'institution' ,'firsttime_check_in', 'lasttime_pay_rent', 'time_pay_rent' , 
    ];
}
