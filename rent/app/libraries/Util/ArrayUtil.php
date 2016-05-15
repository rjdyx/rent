<?php
/**
 * Created by PhpStorm.
 * User: 苏锐佳
 * Date: 2016/5/15
 * Time: 8:45
 */

namespace App\libraries\Util;


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
        if(sizeof($item) > 1){
            $flag = 2;
        }
    }
    return $flag;
}