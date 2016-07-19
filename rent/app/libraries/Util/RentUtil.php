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

    if (isset($householdMsg->time_point)) {//“有房时间点”不为空
        $now_tmp = time();
//        $now_tmp = strtotime('2016-06-26 23:50:50');
        $intervel = $now_tmp - strtotime($householdMsg->time_point);
        if ($intervel >= 0) {
            $days_inc = (int)($intervel / (24 * 60 * 60));
            $householdMsg->incre_count_time += $days_inc;
            $householdMsg->time_point = date('Y-m-d', $now_tmp);
            $householdMsg->save();
        }
    }

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
function calculateOneMonthOneRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房

    $now_tmp = time();
//    $now_tmp = strtotime('2016-06-30 23:50:50');
    $intervel = $now_tmp - strtotime($householdMsg->time_point);
    if ($intervel >= 0) {
        $days_inc = (int)($intervel / (24 * 60 * 60));
        $householdMsg->incre_count_time += $days_inc;
        $householdMsg->time_point = null;
        $householdMsg->save();
    }

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
function calculateLastOneMonthRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent)
{
    date_default_timezone_set('PRC');
    $now = time();//当前时间
    $firsttimeCheckIn = $rent->firsttime_check_in;//第一次入住时间
    $nowY = date('Y', $now);
    $nowM = date('m', $now);
    $firstTimeY = date('Y', strtotime($firsttimeCheckIn));
    $firstTimeM = date('m', strtotime($firsttimeCheckIn));

    if ($nowY == $firstTimeY && $nowM - $firstTimeM >= 1 || $nowY != $firstTimeY) {

        if ($nowY == $firstTimeY && $nowM - $firstTimeM > 1 || $nowY != $firstTimeY) {
            $begin = 1;
        } else if ($nowY == $firstTimeY && $nowM - $firstTimeM == 1) {
            $begin = date('d', strtotime($firsttimeCheckIn));
        }

        $BeginDate = date('Y-m-01', time());//获取月份的第一天
        $endTime = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
        $days = date('t', $endTime);//一个月的天数
        calculateOneRentHasBeginTime($householdMsg, $rent, $begin, $endTime, $days);
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
    $hasHouseOrSubsidy = $householdMsg->has_house_or_subsidy;//是否有房改和补贴


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
    $time = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);


    if ($householdMsg->type == 3) {//博士后
        $config = $rent->addressMsg()->first();//获取租金配置
        $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate) + 20; //计算房租,标准租金+20元/月
        $money = $config->standad_rent_single + $config->standad_rent_decorate;
    } else {
        $money = whichRent($rent, $isDimission, $hasHouse, $time, $householdMsg->in_school_time, $hasHouseOrSubsidy);//获取租金*比例
        if ($money == -1) {
            //1999年12月31日之前进校
            //未享受福利分房且没申请政府住房货币补贴
            //租房面积少于等于75平方用标准租金，超过75的部分用市场租金
            if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')) {
                $config = $rent->addressMsg()->first();//获取租金配置
                if ($rent->area <= 75) {
                    $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate);
                } else {
                    $totalRent = ($intervel / $days) * ($config->standad_rent_single * 75 + $config->standad_rent_decorate) +
                        ($intervel / $days) * $config->market_rent * ($rent->area - 75);
                }
                $money = 0;
            }
        } else {
            $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租
        }
    }

    $rentMsg = new Rent;
    $lasttime_pay_rent = date('Y-m-d H:i:s', $now);
    $rentMsg->firsttime_check_in = $rent->firsttime_check_in;
    $rentMsg->lasttime_pay_rent = ($rent->lasttime_pay_rent == null ? null : $rent->lasttime_pay_rent);
    $rentMsg->time_pay_rent = $lasttime_pay_rent;
    $rentMsg->rent = $totalRent;
    $rentMsg->intervel = $intervel;
    $rentMsg->isDimission = $isDimission;
    $rentMsg->order = $rent->order;
    $rentMsg->hasHouse = $hasHouse;
    $rentMsg->time = $time;
    $rentMsg->region = $rent->regionMsg()->first()->name;
    $rentMsg->address = $rent->addressMsg()->first()->name;
    $rentMsg->room_number = $rent->room_number;
    $rentMsg->money = $money;
    $rentMsg->area = $rent->area;
    $rentMsg->household_id = $householdMsg->id;
    $rentMsg->household_house_id = $rent->id;
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
function calculateOneRentHasBeginTime(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $begin, $now, $days)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房
    $hasHouseOrSubsidy = $householdMsg->has_house_or_subsidy;//是否有房改和补贴

    $end = date('d', $now);
    $intervel = abs($end - $begin) + 1;
    $time = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);


    if ($householdMsg->type == 3) {//博士后
        $config = $rent->addressMsg()->first();//获取租金配置
        $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate) + 20; //计算房租,标准租金+20元/月
        $money = $config->standad_rent_single + $config->standad_rent_decorate;
    } else {
        $money = whichRent($rent, $isDimission, $hasHouse, $time, $householdMsg->in_school_time, $hasHouseOrSubsidy);//获取租金*比例
        if ($money == -1) {
            //1999年12月31日之前进校
            //未享受福利分房且没申请政府住房货币补贴
            //租房面积少于等于75平方用标准租金，超过75的部分用市场租金
            if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')) {
                $config = $rent->addressMsg()->first();//获取租金配置
                if ($rent->area <= 75) {
                    $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate);
                } else {
                    $totalRent = ($intervel / $days) * ($config->standad_rent_single * 75 + $config->standad_rent_decorate) +
                        ($intervel / $days) * $config->market_rent * ($rent->area - 75);
                }
                $money = 0;
            }
        } else {
            $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租
        }
    }


    $rentMsg = new Rent;
    $lasttime_pay_rent = date('Y-m-d H:i:s', $now);
    $rentMsg->firsttime_check_in = $rent->firsttime_check_in;
    $rentMsg->lasttime_pay_rent = ($rent->lasttime_pay_rent == null ? null : $rent->lasttime_pay_rent);
    $rentMsg->time_pay_rent = $lasttime_pay_rent;
    $rentMsg->rent = $totalRent;
    $rentMsg->intervel = $intervel;
    $rentMsg->isDimission = $isDimission;
    $rentMsg->order = $rent->order;
    $rentMsg->hasHouse = $hasHouse;
    $rentMsg->time = $time;
    $rentMsg->region = $rent->regionMsg()->first()->name;
    $rentMsg->address = $rent->addressMsg()->first()->name;
    $rentMsg->room_number = $rent->room_number;
    $rentMsg->money = $money;
    $rentMsg->area = $rent->area;
    $rentMsg->household_id = $householdMsg->id;
    $rentMsg->household_house_id = $rent->id;
    $rentMsg->save();

    $rent->lasttime_pay_rent = $lasttime_pay_rent;
    $rent->save();
}

/**
 * 判断并返回租金*比例
 */
function whichRent(HouseholdHouseMsg $householdHouseMsg, $isDimission, $hasHouse, $time, $inSchoolTime, $hasHouseOrSubsidy)
{
    $config = $householdHouseMsg->addressMsg()->first();
    if (!$isDimission) {//在职
        if ($householdHouseMsg->order == 1) {//第一房
            switch ($hasHouse) {
                case 0: {//无房
                    if (strtotime($inSchoolTime) <= strtotime('1999-12-31')) {
                        if ($hasHouseOrSubsidy) {//是否无房改+补贴
                            return $config->market_rent * 1.2;//有房改+补贴
                        } else {
                            return -1;//无房改+补贴
                        }
                    }
                    if (strtotime($inSchoolTime) < strtotime('2008-06-20')) {
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
                    } else {
                        if (countIntervelTime(strtotime($inSchoolTime)) <= 5) {
                            //周转期5年内（从入校时间开始算，不管有没有租房）用周转租金计算
                            return $config->turnover_rent;
                        } else {
                            if ($time <= 10) {//住房时间<=10年
                                switch (countIntervelTime(strtotime($inSchoolTime)) - 5) {
                                    case 1://5年后的第一年优惠租金*1.0
                                        return $config->discount_rent * 1.0;
                                    case 2://5年后的第二年优惠租金*1.1
                                        return $config->discount_rent * 1.1;
                                    case 3://5年后的第三年优惠租金*1.2
                                        return $config->discount_rent * 1.2;
                                    default://5年后的第四年起优惠租金*1.3
                                        return $config->discount_rent * 1.3;
                                }
                            } else {//住房时间>=11年，用市场租金
                                return $config->market_rent;
                            }
                        }
                    }

                }
                case 1: {//在老八区有商品房
                    if ($time <= 10) {//住房时间<=10年，用市场租金*0.9
                        return $config->market_rent * 0.9;
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

/**
 * 统计现在距离入校时间之间间隔多长时间
 * @param $inSchoolTime
 * @return int
 */
function countIntervelTime($inSchoolTime)
{
    $now = time();
    $intervel = $now - $inSchoolTime;
    $days = (int)($intervel / (24 * 60 * 60));
    $year = (int)($days / 365);
    $yushu = $days % 365;
    if ($yushu == 0) {
        return $year;
    } else {
        return $year + 1;
    }
}

/**
 * 统计累计年限
 * @param $inputCountTime 手动累计时间
 * @param $increCountTime 自动累计时间
 * @return int
 */
function countYears($inputCountTime, $increCountTime)
{
    $arr = explode(".", $inputCountTime);
    if (sizeof($arr) != 3) {
        return 1;
    }
    $days = 0;
    for ($i = 1; $i <= $arr[1]; $i++) {
        if ($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 8 || $i == 10 || $i == 12) {
            $days += 31;
        }
        if ($i == 4 || $i == 6 || $i == 9 || $i == 11) {
            $days += 30;
        }
        if ($i == 2) {
            $days += 28;
        }
    }
    $days += $arr[0] * 365 + $days + $arr[2] + $increCountTime;
    $year = (int)($days / 365);
    $yushu = $days % 365;
    if ($yushu == 0) {
        return $year;
    } else {
        return $year + 1;
    }
}
