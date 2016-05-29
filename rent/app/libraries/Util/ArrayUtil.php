<?php
/**
 * Created by PhpStorm.
 * User: 苏锐佳
 * Date: 2016/5/15
 * Time: 8:45
 */

namespace App\libraries\Util;

use App\Rent;
use App\HouseholdMsg;

/**
 * 将二维数组转成一维数组
 * @param $arr
 */
function array_two_to_one($arr)
{
    $oneArr = array();
    for ($index = 0; $index < sizeof($arr); $index++) {
        $oneArr['' . $arr[$index]['name']] = $arr[$index]['value'];
    }
    return $oneArr;
}

/**
 * 判断数组是一维还是二维数组
 */
function what_kind_of_array($arr)
{
    $flag = 1;//默认一维
    foreach ($arr as $item) {
        $len = sizeof($item);
        if (sizeof($item) > 1) {
            $flag = 2;
        }
    }
    return $flag;
}

function addRentToArray($arr, Rent $rents, HouseholdMsg $householdMsg)
{
    foreach ($rents as $rent) {
        $tmp[0] = $householdMsg->job_number;
        $tmp[1] = $householdMsg->name;
        $tmp[2] = $householdMsg->institution;
        $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
        $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
        $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
        $tmp[6] = $rent->rent;
        $tmp[7] = $rent->formulas;
        array_push($arr, $tmp);
    }
}