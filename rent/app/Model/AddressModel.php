<?php
/**
 * Created by PhpStorm.
 * User: 苏锐佳
 * Date: 2016/5/14
 * Time: 16:23
 */

namespace App\Model;


class AddressModel extends BaseFormModel
{
    public $AddressName;
    public $TurnoverRent;
    public $DiscountRent;
    public $MarketRent;
    public $StandadRentSingle;
    public $StandadRentDecorate;
    public $ParentId;
    //与数据库中表的字段对应
    public $map = array(
        'AddressName' => 'name',
        'TurnoverRent' => 'turnover_rent',
        'DiscountRent' => 'discount_rent',
        'MarketRent' => 'market_rent',
        'StandadRentSingle' => 'standad_rent_single',
        'StandadRentDecorate' => 'standad_rent_decorate',
        'ParentId' => 'parent_id'
    );

    public function __construct($input)
    {
        //验证规则
        $rule = array(
            'AddressName' => 'required|between:2,20',
            'TurnoverRent' => 'required|numeric',
            'DiscountRent' => 'required|numeric',
            'MarketRent' => 'required|numeric',
            'StandadRentSingle' => 'required|numeric',
            'StandadRentDecorate' => 'required|numeric',
            'ParentId' => 'required'
        );

        $this->init($input, $rule);
    }
}