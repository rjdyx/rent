<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\Model\AddressModel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB;
use Validator;
use Illuminate\Support\Facades\Input;
use App\libraries\Util;

class EditConfigController extends Controller
{

    /**
     * 进入配置页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editConfig(Request $request)
    {
        $arrTotal = array();

        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();

        for ($i = 0; $i < sizeof($areas); $i++) {
            $arrItem = array();
            $arrItem[0] = $areas[$i];
            $addresses = Config::select('id', 'name', 'turnover_rent', 'discount_rent', 'market_rent', 'standad_rent_single', 'standad_rent_decorate')
                ->where('parent_id', '=', $areas[$i]->id)
                ->orderBy('turnover_rent', 'asc')
                ->orderBy('discount_rent', 'asc')
                ->orderBy('market_rent', 'asc')
                ->orderBy('standad_rent_single', 'asc')
                ->orderBy('standad_rent_decorate', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
            if(sizeof($addresses) == 0){
                $arrTotal[$i] = $arrItem;
                continue;
            }
            $index = 1;
            $same = array();
            $turnover_rent = $addresses[0]->turnover_rent;
            $discount_rent = $addresses[0]->discount_rent;
            $market_rent = $addresses[0]->market_rent;
            $standad_rent_single = $addresses[0]->standad_rent_single;
            $standad_rent_decorate = $addresses[0]->standad_rent_decorate;
            for ($j = 0; $j < sizeof($addresses); $j++) {
                if ($turnover_rent == $addresses[$j]->turnover_rent &&
                    $discount_rent == $addresses[$j]->discount_rent &&
                    $market_rent == $addresses[$j]->market_rent &&
                    $standad_rent_single == $addresses[$j]->standad_rent_single &&
                    $standad_rent_decorate == $addresses[$j]->standad_rent_decorate
                ) {
                    array_push($same, $addresses[$j]);
                    if($j == sizeof($addresses)-1){
                        $arrItem[$index] = $same;
                    }
                } else {
                    $turnover_rent = $addresses[$j]->turnover_rent;
                    $discount_rent = $addresses[$j]->discount_rent;
                    $market_rent = $addresses[$j]->market_rent;
                    $standad_rent_single = $addresses[$j]->standad_rent_single;
                    $standad_rent_decorate = $addresses[$j]->standad_rent_decorate;
                    $arrItem[$index] = $same;
                    $index++;
                    $same = array();
                    array_push($same, $addresses[$j]);
                    if($j == sizeof($addresses)-1){
                        $arrItem[$index] = $same;
                    }
                }
            }
            $arrTotal[$i] = $arrItem;
        }
        return view('option.editConfig',
            [
                'arrTotal' => $arrTotal,
                'active' => 'editConfig',
                'sildedown' => 'editConfig'
            ]);
    }

    /**
     *新增区域
     */
    public function addArea(Request $Request)
    {
        $name = $Request->input('areaName');
        $valid = Validator::make(
            array('name' => $name),
            array('name' => 'required|between:2,20|unique:configs,name')
        );
        if (!$valid->fails()) {
            Config::create(['name' => $name]);
            return response()->json('success');
        } else {
            return response()->json('fail');
        }

    }

    /**
     * 新增房址
     * @param Request $request
     */
    public function addAddress(Request $request)
    {
        $data = Input::All();
        $addressForm = new AddressModel($data);
        $DBarr = $addressForm->getDBArray();

        if ($addressForm->isValid()) {
            $config = Config::create($DBarr);
            return response()->json('success');
        } else {
            return response()->json('fail');
        }

    }

    /**
     * 获取所有区域
     */
    public function getAllArea()
    {
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($areas);
    }

    /**
     * 获取所有区域以及选择区域
     * @param Request $request
     */
    public function getAllAreaAndCheckOne(Request $request)
    {
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();
        $addressId = $request->input('id');
        $address = Config::where('id', '=', $addressId)
            ->first();
        return response()->json(['areas' => $areas, 'areaId' => $address->parent_id]);
    }

    /**
     * 获取所有区域和房址
     */
    public function getAllAreaAndAddress()
    {
        $arrTotal = array();

        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();

        for ($i = 0; $i < sizeof($areas); $i++) {
            $arrItem = array();
            $arrItem[0] = $areas[$i];
            $addresses = Config::select('id', 'name', 'turnover_rent', 'discount_rent', 'market_rent', 'standad_rent_single', 'standad_rent_decorate')
                ->where('parent_id', '=', $areas[$i]->id)
                ->orderBy('turnover_rent', 'asc')
                ->orderBy('discount_rent', 'asc')
                ->orderBy('market_rent', 'asc')
                ->orderBy('standad_rent_single', 'asc')
                ->orderBy('standad_rent_decorate', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
            if(sizeof($addresses) == 0){
                $arrTotal[$i] = $arrItem;
                continue;
            }
            $index = 1;
            $same = array();
            $turnover_rent = $addresses[0]->turnover_rent;
            $discount_rent = $addresses[0]->discount_rent;
            $market_rent = $addresses[0]->market_rent;
            $standad_rent_single = $addresses[0]->standad_rent_single;
            $standad_rent_decorate = $addresses[0]->standad_rent_decorate;
            for ($j = 0; $j < sizeof($addresses); $j++) {
                if ($turnover_rent == $addresses[$j]->turnover_rent &&
                    $discount_rent == $addresses[$j]->discount_rent &&
                    $market_rent == $addresses[$j]->market_rent &&
                    $standad_rent_single == $addresses[$j]->standad_rent_single &&
                    $standad_rent_decorate == $addresses[$j]->standad_rent_decorate
                ) {
                    array_push($same, $addresses[$j]);
                    if($j == sizeof($addresses)-1){
                        $arrItem[$index] = $same;
                    }
                } else {
                    $turnover_rent = $addresses[$j]->turnover_rent;
                    $discount_rent = $addresses[$j]->discount_rent;
                    $market_rent = $addresses[$j]->market_rent;
                    $standad_rent_single = $addresses[$j]->standad_rent_single;
                    $standad_rent_decorate = $addresses[$j]->standad_rent_decorate;
                    $arrItem[$index] = $same;
                    $index++;
                    $same = array();
                    array_push($same, $addresses[$j]);
                    if($j == sizeof($addresses)-1){
                        $arrItem[$index] = $same;
                    }
                }
            }
            $arrTotal[$i] = $arrItem;
        }
        return response()->json($arrTotal);
    }

    /**
     * 编辑区域名
     * @param Request $request
     */
    public function editArea(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $result = Config::where('id', '=', $id)
            ->update(['name' => $name]);
        if ($result == 1) {
            return response()->json('success');
        } else {
            return response()->json('fail');
        }
    }

    /**
     * 编辑房址
     * @param Request $request
     */
    public function editAddress(Request $request)
    {
        $data = Input::All();
        $addressForm = new AddressModel($data);
        $DBarr = $addressForm->getDBArray();

        if ($addressForm->isValid()) {
            $config = Config::where('id', '=', $data['id'])
                ->update($DBarr);
            return response()->json('success');
        } else {
            return response()->json('fail');
        }
    }

    /**
     * 删除区域
     * @param $id
     */
    public function deleteArea($id)
    {
        $result = Config::where('id', '=', $id)
            ->delete();
        Config::where('parent_id', '=', $id)
            ->delete();
        if ($result > 0) {
            return response()->json('success');
        } else {
            return response()->json('fail');
        }
    }

    /**
     * 删除房址
     * @param $id
     */
    public function deleteAddress($id)
    {
        $result = Config::where('id', '=', $id)
            ->delete();
        if ($result > 0) {
            return response()->json('success');
        } else {
            return response()->json('fail');
        }
    }


}
