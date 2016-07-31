<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HouseholdHouseRelation extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'household_house_relation';


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
        'status', 'household_id', 'household_house_id'
    ];

    /**
     * 获取区域名
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function householdMsg(){
        return $this->belongsTo('App\HouseholdMsg','household_id','id');
    }

    public function householdHouseMsg(){
        return $this->belongsTo('App\HouseholdHouseMsg','household_house_id','id');
    }

}
