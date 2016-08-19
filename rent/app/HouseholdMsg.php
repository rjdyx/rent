<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseholdMsg extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'household_msg';


    /**
     * 模型日期列的存储格式
     *
     * @var string
     */
//    protected $dateFormat = 'U';


    /**
     * The connection name for the model.
     *
     * @var string
     */
//    protected $connection = 'connection-name';


    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'job_number', 'card_number', 'institution' ,'has_house', 'has_house_time','has_not_house_time','is_dimission','has_house_or_subsidy','dimission_time','type','privilege'
    ];

    /**
     * 获取住户所有的租房信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function householdHouseMsg(){
        return $this->hasMany('App\HouseholdHouseMsg','household_id');
    }

    /**
     * 获取房租信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rent(){
        return $this->hasMany('App\Rent','household_id');
    }
}
