<?php
/**
 * Created by PhpStorm.
 * User: 苏锐佳
 * Date: 2016/5/19
 * Time: 21:47
 */

namespace App\libraries\Util;


use App\HouseholdHouseMsg;
use App\HouseholdMsg;
use App\Rent;

/**
 * 统计一位住户所有租房的一个月的房租
 * 1、月初入住，月底结算
 * 2、月中入住，月底结算
 * 3、月底入住，月底结算
 * 4、月初入住，月中结算
 * 5、月中入住，月中结算
 */
function calculateOneMonthRent(HouseholdMsg $householdMsg, $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房
    $rents = $householdMsg->householdHouseMsg()
        ->where('is_check_out', '=', 0)
        ->orderBy('order', 'asc')
        ->get();
    foreach ($rents as $rent) {
        calculateOneRent($householdMsg, $rent, $now, $days);
    }
}

/**
 * 统计一位住户一间租房的一个月的房租
 * 1、月初入住，月底结算
 * 2、月中入住，月底结算
 * 3、月底入住，月底结算
 * 4、月初入住，月中结算
 * 5、月中入住，月中结算
 */
function calculateOneMonthOneRent(HouseholdMsg $householdMsg,HouseholdHouseMsg $rent, $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房
    calculateOneRent($householdMsg, $rent, $now, $days);
}

/**
 * 统计一间租房所有月份的房租
 */
function calculateAllMonthRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent)
{
    date_default_timezone_set('PRC');
    $now = time();//当前时间
    $firsttimeCheckIn = $rent->firsttime_check_in;//第一次入住时间
    $nowY = date('Y', $now);
    $nowM = date('m', $now);
    $firstTimeY = date('Y', strtotime($firsttimeCheckIn));
    $firstTimeM = date('m', strtotime($firsttimeCheckIn));

    if ($nowY != $firstTimeY || $nowM != $firstTimeM) {
        for ($i = 0; $i < $nowM + ($nowY - $firstTimeY) * 12 - $firstTimeM; $i++) {
            if ($rent->lasttime_pay_rent == null) {
                $BeginDate = date('Y-m-01', strtotime($firsttimeCheckIn));//获取月份的第一天
                $endTime = strtotime("$BeginDate +1 month -1 day");//获取月份的最后一天
                $days = date('t', $endTime);//一个月的天数
            } else {
                $BeginDate = date('Y-m-d', strtotime($rent->lasttime_pay_rent) + (24 * 60 * 60));//获取月份的第一天
                $endTime = strtotime("$BeginDate +1 month -1 day");//获取月份的最后一天
                $days = date('t', $endTime);//一个月的天数
            }
            calculateOneRent($householdMsg, $rent, $endTime, $days);
        }

    }
}

/**
 * 统计一间房上个月的房租
 * @param HouseholdMsg $householdMsg
 * @param HouseholdHouseMsg $rent
 */
function calculateLastOneMonthRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent){
    date_default_timezone_set('PRC');
    $now = time();//当前时间
    $firsttimeCheckIn = $rent->firsttime_check_in;//第一次入住时间
    $nowY = date('Y', $now);
    $nowM = date('m', $now);
    $firstTimeY = date('Y', strtotime($firsttimeCheckIn));
    $firstTimeM = date('m', strtotime($firsttimeCheckIn));

    if ($nowY == $firstTimeY && $nowM - $firstTimeM >= 1 || $nowY != $firstTimeY) {
        
        if($nowY == $firstTimeY && $nowM - $firstTimeM > 1 || $nowY != $firstTimeY){
            $begin = 1;
        }else if($nowY == $firstTimeY && $nowM - $firstTimeM == 1){
            $begin = date('d', strtotime($firsttimeCheckIn));
        }

        $BeginDate = date('Y-m-01', time());//获取月份的第一天
        $endTime = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
        $days = date('t', $endTime);//一个月的天数
        calculateOneRentHasBeginTime($householdMsg,$rent,$begin,$endTime,$days);
    }

}

/**
 * 统计一间租房一个月的房租
 * @param HouseholdMsg $householdMsg
 * @param HouseholdHouseMsg $rent
 * @param $now
 * @param $days
 */
function calculateOneRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房

    $time = abs(date('Y') - date('Y', strtotime($rent->firsttime_check_in)));
    $money = whichRent($rent, $isDimission, $hasHouse, $time);//获取租金*比例

    if ($rent->lasttime_pay_rent == null) {//当月才入住，且还没有结算记录
        $begin = date('d', strtotime($rent->firsttime_check_in));
    } else {
        $oldMonth = date('m', strtotime($rent->lasttime_pay_rent));
        $newMonth = date('m', strtotime($rent->lasttime_pay_rent) + (24 * 60 * 60));
        if ($newMonth == $oldMonth) {//判断结算时间是否是月底
            $begin = date('d', strtotime($rent->lasttime_pay_rent));
        } else {
            $begin = 1;
        }
    }
    $end = date('d', $now);
    $intervel = abs($end - $begin) + 1;
    $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租

    $rentMsg = new Rent;
    $lasttime_pay_rent = date('Y-m-d H:i:s', $now);
    $rentMsg->firsttime_check_in = $rent->firsttime_check_in;
    $rentMsg->lasttime_pay_rent = ($rent->lasttime_pay_rent == null? null:$rent->lasttime_pay_rent);
    $rentMsg->time_pay_rent = $lasttime_pay_rent;
    $rentMsg->rent = $totalRent;
    $formulas =
        '天数：' . $intervel .
        '，是否离职：' . ($isDimission == 0 ? '否' : '是') .
        '，租房：' . $rent->order .
        '，是否有房：' . ($hasHouse == 0 ? '无房' : ($hasHouse == 1 ? '商品房' : '房改房')) .
        '，年限：' . $time .
        '，区域：' . $rent->regionMsg()->first()->name .
        '，房址：' . $rent->addressMsg()->first()->name .
        '，租金x比例：' . $money .
        '，面积：' . $rent->area;
    $rentMsg->formulas = $formulas;
    $rentMsg->household_id = $householdMsg->id;
    $rentMsg->save();

    $rent->lasttime_pay_rent = $lasttime_pay_rent;
    $rent->save();
}

/**
 * 统计一间租房一个月的房租
 * @param HouseholdMsg $householdMsg
 * @param HouseholdHouseMsg $rent
 * @param $now
 * @param $days
 */
function calculateOneRentHasBeginTime(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $begin , $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房

    $time = abs(date('Y') - date('Y', strtotime($rent->firsttime_check_in)));
    $money = whichRent($rent, $isDimission, $hasHouse, $time);//获取租金*比例

    $end = date('d', $now);
    $intervel = abs($end - $begin) + 1;
    $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租

    $rentMsg = new Rent;
    $lasttime_pay_rent = date('Y-m-d H:i:s', $now);
    $rentMsg->firsttime_check_in = $rent->firsttime_check_in;
    $rentMsg->lasttime_pay_rent = ($rent->lasttime_pay_rent == null? null:$rent->lasttime_pay_rent);
    $rentMsg->time_pay_rent = $lasttime_pay_rent;
    $rentMsg->rent = $totalRent;
    $formulas =
        '天数：' . $intervel .
        '，是否离职：' . ($isDimission == 0 ? '否' : '是') .
        '，租房：' . $rent->order .
        '，是否有房：' . ($hasHouse == 0 ? '无房' : ($hasHouse == 1 ? '商品房' : '房改房')) .
        '，年限：' . $time .
        '，区域：' . $rent->regionMsg()->first()->name .
        '，房址：' . $rent->addressMsg()->first()->name .
        '，租金x比例：' . $money .
        '，面积：' . $rent->area;
    $rentMsg->formulas = $formulas;
    $rentMsg->household_id = $householdMsg->id;
    $rentMsg->save();

    $rent->lasttime_pay_rent = $lasttime_pay_rent;
    $rent->save();
}

/**
 * 判断并返回租金*比例
 */
function whichRent(HouseholdHouseMsg $householdHouseMsg, $isDimission, $hasHouse, $time)
{
    $config = $householdHouseMsg->addressMsg()->first();
    if (!$isDimission) {//在职
        if ($householdHouseMsg->order == 1) {//第一房
            switch ($hasHouse) {
                case 0: {//无房
                    if ($time <= 5) {//住房时间<=5年，用周转租金
                        return $config->turnover_rent;
                    } else if ($time == 6) {//住房时间==6年，优惠租金*1.0
                        return $config->discount_rent * 1.0;
                    } else if ($time == 7) {//住房时间==7年，优惠租金*1.1
                        return $config->discount_rent * 1.1;
                    } else if ($time == 8) {//住房时间==8年，优惠租金*1.2
                        return $config->discount_rent * 1.2;
                    } else if ($time == 9 || $time == 10) {//住房时间==9或10年，优惠租金*1.3
                        return $config->discount_rent * 1.3;
                    } else {//住房时间>=11年，用市场租金
                        return $config->market_rent;
                    }
                }
                case 1: {//有商品房
                    if ($time <= 5) {//住房时间<=5年，用周转租金
                        return $config->turnover_rent;
                    } else if ($time >= 6 && $time <= 10) {//住房时间>=6年&&<=10年，优惠租金*0.9
                        return $config->discount_rent * 0.9;
                    } else {//住房时间>=11年，用市场租金
                        return $config->market_rent * 1.0;
                    }
                }
                case 2: {//有房改房
                    //return 市场租金*1.2
                    return $config->market_rent * 1.2;
                }
            }
        } else {//第二房
            //return 市场租金*1.2
            return $config->market_rent * 1.2;
        }

    } else {//离职
        //return 市场租金*1.4
        return $config->market_rent * 1.4;
    }
}
