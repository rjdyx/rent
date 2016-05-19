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
        'region', 'address', 'area', 'firsttime_check_in', 'lasttime_pay_rent', 'is_check_out', 'order','household_id'
    ];

    /**
     * 获取租房对应的住户信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function householdMsg(){
        return $this->belongsTo('App\HouseholdMsg');
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
