<?php
/**
 * Created by PhpStorm.
 * User: 苏锐佳
 * Date: 2016/5/13
 * Time: 22:41
 */

namespace App\Model;
use Validator;


abstract class BaseFormModel
{

    private $_validator;
    private $arr = array();

    protected function init($input, $rule = array())
    {
        //将二维数组转化成一维数组
        if(\App\libraries\Util\what_kind_of_array($input) == 2){
            $input = \App\libraries\Util\array_two_to_one($input);
        }

        //验证
        $this->_validator = Validator::make($input, $rule);

        $formKey = array_keys(get_class_vars(get_class($this)));

        // 遍历表单键值 并赋予类成员
        foreach ($formKey as $value)
        {
            if(isset($input[$value]))
            {
                //将表单中的对应的name的值赋予对应的属性
                $this->$value = $input[$value];

                //将表单中的对应的name的值赋予对应的字段
                if(isset($this->map[$value])){
                    $this->arr[$this->map[$value]] = $this->$value;
                }
            }
        }
    }

    public function validator()
    {
        return $this->_validator;
    }

    public function isValid()
    {
        return !$this->_validator->fails();
    }

    /**
     * 获取与数据库字段名相同的数组
     */
    public function getDBArray(){
        return $this->arr;
    }


}