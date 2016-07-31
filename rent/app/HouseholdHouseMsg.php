<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseholdHouseMsg extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'household_house_msg';


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
        'region_id', 'address_id', 'area', 'firsttime_check_in', 'lasttime_pay_rent','room_number', 'order','remark'
    ];

    /**
     * 获取租房所有的住户关联实体
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function householdHouseRelation(){
        return $this->hasMany('App\HouseholdHouseRelation','household_house_id');
    }

    /**
     * 获取区域名
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function regionMsg(){
        return $this->belongsTo('App\Config','region_id','id');
    }

    public function addressMsg(){
        return $this->belongsTo('App\Config','address_id','id');
    }
}
