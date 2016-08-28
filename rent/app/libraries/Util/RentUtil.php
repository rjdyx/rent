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
    $isDifferent = 0;//0:同月同租金,1:08年之前同月不同租金且只考虑累计租房时间,2:08年之后同月不同租金且只考虑累计租房时间,3:08年之前同月不同租金且只考虑入校时间
    $differentDays = 0;

    if (isset($householdMsg->time_point)) {//“有房时间点”不为空
        $now_tmp = time();
//        $now_tmp = strtotime('2016-08-31 23:50:50');
        $intervel = $now_tmp - strtotime($householdMsg->time_point);
        if ($intervel >= 0) {
            $days_inc = (int)($intervel / (24 * 60 * 60));

            if (strtotime($householdMsg->in_school_time) < strtotime('2008-06-20')) {
                //判断一个月内是否出现不同的累计年限
                $oldYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);
                $newYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time + $days_inc);
                if ($oldYear != $newYear) {
                    $isDifferent = 1;
                    $differentDays = countDaysLeft($householdMsg->input_count_time, $householdMsg->incre_count_time);
                }
            } else {
                $oldYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);
                $newYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time + $days_inc);
                if ($oldYear != $newYear && $newYear > 10) {
                    $isDifferent = 2;
                    $differentDays = countDaysLeft($householdMsg->input_count_time, $householdMsg->incre_count_time);
                } else {
                    $BeginDate = date('Y-m-01', time());//获取当月份的第一天
                    $lastMonthEndDay = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
                    $oldSchoolTime = countIntervelTimeWithNow($lastMonthEndDay, strtotime($householdMsg->in_school_time)) - 5;
                    $newSchoolTime = countIntervelTime(strtotime($householdMsg->in_school_time)) - 5;
                    if ($oldSchoolTime > 0 && $newSchoolTime > 0 && $oldSchoolTime != $newSchoolTime) {
                        $isDifferent = 3;
                    }
                }
            }

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
        calculateOneRent($householdMsg, $rent, $now, $days, $isDifferent, $differentDays);
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
    $isDifferent = 0;//0:同月同租金,1:08年之前同月不同租金且只考虑累计租房时间,2:08年之后同月不同租金且只考虑累计租房时间,3:08年之前同月不同租金且只考虑入校时间
    $differentDays = 0;

    $now_tmp = time();
//    $now_tmp = strtotime('2017-04-15 12:00:00');
    $intervel = $now_tmp - strtotime($householdMsg->time_point);
    if ($intervel >= 0) {
        $days_inc = (int)($intervel / (24 * 60 * 60));

        if (strtotime($householdMsg->in_school_time) < strtotime('2008-06-20')) {
            //判断一个月内是否出现不同的累计年限
            $oldYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);
            $newYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time + $days_inc);
            if ($oldYear != $newYear) {
                $isDifferent = 1;
                $differentDays = countDaysLeft($householdMsg->input_count_time, $householdMsg->incre_count_time);
            }
        } else {
            $oldYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);
            $newYear = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time + $days_inc);
            if ($oldYear != $newYear && $newYear > 10) {
                $isDifferent = 2;
                $differentDays = countDaysLeft($householdMsg->input_count_time, $householdMsg->incre_count_time);
            } else {
                $BeginDate = date('Y-m-01', time());//获取当月份的第一天
                $lastMonthEndDay = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
                $oldSchoolTime = countIntervelTimeWithNow($lastMonthEndDay, strtotime($householdMsg->in_school_time)) - 5;
                $newSchoolTime = countIntervelTime(strtotime($householdMsg->in_school_time)) - 5;
                if ($oldSchoolTime > 0 && $newSchoolTime > 0 && $oldSchoolTime != $newSchoolTime) {
                    $isDifferent = 3;
                }
            }
        }


        $householdMsg->incre_count_time += $days_inc;
        $householdMsg->time_point = null;
        $householdMsg->save();
    }

    calculateOneRent($householdMsg, $rent, $now, $days, $isDifferent, $differentDays);
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
//    $now = strtotime('2017-08-23 12:00:00');
    $firsttimeCheckIn = $rent->firsttime_check_in;//第一次入住时间
    $nowY = date('Y', $now);
    $nowM = date('m', $now);
    $firstTimeY = date('Y', strtotime($firsttimeCheckIn));
    $firstTimeM = date('m', strtotime($firsttimeCheckIn));

    //判断第一次入住时间是否是当月，是当月则不计算上个月房租，否则计算上个月房租
    if ($nowY == $firstTimeY && $nowM - $firstTimeM >= 1 || $nowY != $firstTimeY) {

        if ($nowY == $firstTimeY && $nowM - $firstTimeM > 1 || $nowY != $firstTimeY) {
            $begin = 1;
        } else if ($nowY == $firstTimeY && $nowM - $firstTimeM == 1) {
            $begin = date('d', strtotime($firsttimeCheckIn));
        }

        $BeginDate = date('Y-m-01', time());//获取月份的第一天
        $endTime = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
        $days = date('t', $endTime);//一个月的天数

        $isDifferent = 0;//0:同月同租金,1:08年之前同月不同租金且只考虑累计租房时间,2:08年之后同月不同租金且只考虑累计租房时间,3:08年之前同月不同租金且只考虑入校时间,4:用减去一年的房租
        $differentDays = 0;
        if (strtotime($householdMsg->in_school_time) < strtotime('2008-06-20')) {
            //判断一个月内是否出现不同的累计年限
            if ($householdMsg->input_count_time == null) {
                $householdMsg->input_count_time = '0.0.0';
            }
            $arr = explode(".", $householdMsg->input_count_time);
            if (sizeof($arr) != 3) {
                $arr[0] = 0;
                $arr[1] = 0;
                $arr[2] = 0;
            }
            if ($arr[1] <= 1) {
                $BeginDate = date('Y-m-01', time());//获取当月份的第一天
                $lastMonthEndDay = date('d', strtotime("$BeginDate -1 day"));//获取上个月份的最后一天
                $nowMonthDays = date('d', time());//获取当月份的天数
                $differentDays = ($nowMonthDays + $lastMonthEndDay) - ($arr[1] * 31 + $arr[2]);
                if ($differentDays > 0 && $differentDays < $lastMonthEndDay) {
                    $isDifferent = 1;
                } else if ($differentDays >= $lastMonthEndDay) {
                    $isDifferent = 4;
                }
            }
        } else {
            if ($householdMsg->input_count_time == null) {
                $householdMsg->input_count_time = '0.0.0';
            }
            $arr = explode(".", $householdMsg->input_count_time);
            if (sizeof($arr) != 3) {
                $arr[0] = 0;
                $arr[1] = 0;
                $arr[2] = 0;
            }
            if ($arr[0] > 10) {
                if ($arr[1] <= 1) {
                    $BeginDate = date('Y-m-01', time());//获取当月份的第一天
                    $lastMonthEndDay = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
                    $nowMonthDays = date('d', time());//获取当月份的天数
                    $differentDays = ($nowMonthDays + $lastMonthEndDay) - ($arr[1] * 31 + $arr[2]);
                    if ($differentDays > 0 && $differentDays < $lastMonthEndDay) {
                        $isDifferent = 2;
                    } else if ($differentDays >= $lastMonthEndDay) {
                        $isDifferent = 4;
                    }
                }
            } else {
                $BeginDate = date('Y-m-01', time());//获取当月份的第一天
                $lastMonthEndDay = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
                $m = date('Y-m-d', $lastMonthEndDay);
                $lastLastMonthEndDay = strtotime("$BeginDate -1 month -1 day");//获取上上个月份的最后一天
                $n = date('Y-m-d', $lastLastMonthEndDay);
                $oldSchoolTime = countIntervelTimeWithNow($lastLastMonthEndDay, strtotime($householdMsg->in_school_time)) - 5;
                $newSchoolTime = countIntervelTimeWithNow($lastMonthEndDay, strtotime($householdMsg->in_school_time)) - 5;
                $nowSchoolTime = countIntervelTimeWithNow(time(), strtotime($householdMsg->in_school_time)) - 5;
                if ($oldSchoolTime > 0 && $newSchoolTime > 0 && $oldSchoolTime != $newSchoolTime) {
                    $isDifferent = 3;
                }
                if ($nowSchoolTime > 0 && $newSchoolTime > 0 && $nowSchoolTime != $newSchoolTime) {
                    $isDifferent = 4;
                }
            }
        }

        calculateOneRentHasBeginTime($householdMsg, $rent, $begin, $endTime, $days, $isDifferent, $differentDays);
    }

}

/**
 * 统计一间租房一个月的房租
 * @param HouseholdMsg $householdMsg
 * @param HouseholdHouseMsg $rent
 * @param $now
 * @param $days
 */
function calculateOneRent(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $now, $days, $isDifferent, $differentDays)
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


    if ($householdMsg->privilege == 1) {//享受标租
        $config = $rent->addressMsg()->first();//获取租金配置
        if ($rent->area <= 75) {
            $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate);
        } else {
            $totalRent = ($intervel / $days) * ($config->standad_rent_single * 75 + $config->standad_rent_decorate) +
                ($intervel / $days) * $config->market_rent * ($rent->area - 75);
        }
        $money = 0;
    } else if ($householdMsg->type == 3) {//博士后
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
            if ($isDifferent == 1 || $isDifferent == 2) {
                $oldMoney = whichRent($rent, $isDimission, $hasHouse, $time - 1, $householdMsg->in_school_time, $hasHouseOrSubsidy);//获取旧的租金*比例
                $totalRent = ($differentDays / $days) * $money * $rent->area + (($intervel - $differentDays) / $days) * $oldMoney * $rent->area; //计算房租
            } else if ($isDifferent == 3) {
                $config = $rent->addressMsg()->first();//获取租金配置
                $oldMoney = $money - $config->discount_rent * 0.1;
                $differentDays = date('d', strtotime($householdMsg->in_school_time));
                $totalRent = ($differentDays / $days) * $oldMoney * $rent->area + (($intervel - $differentDays) / $days) * $money * $rent->area; //计算房租
            } else {
                $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租
            }
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
function calculateOneRentHasBeginTime(HouseholdMsg $householdMsg, HouseholdHouseMsg $rent, $begin, $now, $days, $isDifferent, $differentDays)
{
    $jobNumber = $householdMsg->job_number;//工号
    $name = $householdMsg->name;//姓名
    $isDimission = $householdMsg->is_dimission;//是否离职
    $hasHouse = $householdMsg->has_house;//是否有房
    $hasHouseOrSubsidy = $householdMsg->has_house_or_subsidy;//是否有房改和补贴

    $end = date('d', $now);
    $intervel = abs($end - $begin) + 1;
    $time = countYears($householdMsg->input_count_time, $householdMsg->incre_count_time);


    if ($householdMsg->privilege == 1) {//享受标租
        $config = $rent->addressMsg()->first();//获取租金配置
        if ($rent->area <= 75) {
            $totalRent = ($intervel / $days) * ($config->standad_rent_single * $rent->area + $config->standad_rent_decorate);
        } else {
            $totalRent = ($intervel / $days) * ($config->standad_rent_single * 75 + $config->standad_rent_decorate) +
                ($intervel / $days) * $config->market_rent * ($rent->area - 75);
        }
        $money = 0;
    } else if ($householdMsg->type == 3) {//博士后
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
            if ($isDifferent == 1 || $isDifferent == 2) {
                $oldMoney = whichRent($rent, $isDimission, $hasHouse, $time - 1, $householdMsg->in_school_time, $hasHouseOrSubsidy);//获取旧的租金*比例
                $totalRent = ($differentDays / $days) * $oldMoney * $rent->area + (($intervel - $differentDays) / $days) * $money * $rent->area; //计算房租
            } else if ($isDifferent == 3) {
                $config = $rent->addressMsg()->first();//获取租金配置
                $oldMoney = $money - $config->discount_rent * 0.1;
                $differentDays = date('d', strtotime($householdMsg->in_school_time));
                $totalRent = ($differentDays / $days) * $oldMoney * $rent->area + (($intervel - $differentDays) / $days) * $money * $rent->area; //计算房租
            } else if ($isDifferent == 4) {
                $time = $time - 1;
                $oldMoney = whichRent($rent, $isDimission, $hasHouse, $time, $householdMsg->in_school_time, $hasHouseOrSubsidy);//获取租金*比例
                $totalRent = ($intervel / $days) * $oldMoney * $rent->area; //计算房租
            } else {
                $totalRent = ($intervel / $days) * $money * $rent->area; //计算房租
            }
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
 * 统计自定义时间点距离入校时间之间间隔多长时间
 * @param $inSchoolTime
 * @return int
 */
function countIntervelTimeWithNow($time, $inSchoolTime)
{
    $intervel = $time - $inSchoolTime;
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
    if ($increCountTime == null) {
        $increCountTime = 0;
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
    $days = $arr[0] * 365 + $days + $arr[2] + $increCountTime;
    $year = (int)($days / 365);
    $yushu = $days % 365;
    if ($yushu == 0) {
        return $year;
    } else {
        return $year + 1;
    }
}

function countDaysLeft($inputCountTime, $increCountTime)
{
    $arr = explode(".", $inputCountTime);
    if (sizeof($arr) != 3) {
        return 1;
    }

    if ($increCountTime == null) {
        $increCountTime = 0;
    }

    $year = 0;
    $month = 0;
    $day = 0;

    $year = (int)($increCountTime / 365);
    $yushu = $increCountTime % 365;
    if ($yushu <= 31) {//第一个月
        $month = (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 31 && $yushu <= 59) {//第二个月
        $yushu -= 31;
        $month = 1 + (int)($yushu / 28);
        $day = ($yushu == 28 ? 0 : $yushu);
    } else if ($yushu > 59 && $yushu <= 90) {//第三个月
        $yushu -= 59;
        $month = 2 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 90 && $yushu <= 120) {//第四个月
        $yushu -= 90;
        $month = 3 + (int)($yushu / 30);
        $day = ($yushu == 30 ? 0 : $yushu);
    } else if ($yushu > 120 && $yushu <= 151) {//第五个月
        $yushu -= 120;
        $month = 4 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 151 && $yushu <= 181) {//第六个月
        $yushu -= 151;
        $month = 5 + (int)($yushu / 30);
        $day = ($yushu == 30 ? 0 : $yushu);
    } else if ($yushu > 181 && $yushu <= 212) {//第七个月
        $yushu -= 181;
        $month = 6 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 212 && $yushu <= 243) {//第八个月
        $yushu -= 212;
        $month = 7 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 243 && $yushu <= 273) {//第九个月
        $yushu -= 243;
        $month = 8 + (int)($yushu / 30);
        $day = ($yushu == 30 ? 0 : $yushu);
    } else if ($yushu > 273 && $yushu <= 304) {//第十个月
        $yushu -= 273;
        $month = 9 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    } else if ($yushu > 304 && $yushu <= 334) {//第十一个月
        $yushu -= 304;
        $month = 10 + (int)($yushu / 30);
        $day = ($yushu == 30 ? 0 : $yushu);
    } else if ($yushu > 334 && $yushu <= 365) {//第十二个月
        $yushu -= 334;
        $month = 11 + (int)($yushu / 31);
        $day = ($yushu == 31 ? 0 : $yushu);
    }

    $totalDays = $arr[2] + $day;
    return 31 - $totalDays;
}
