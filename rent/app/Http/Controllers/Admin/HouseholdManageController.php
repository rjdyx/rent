<?php

namespace App\Http\Controllers\Admin;

use App\HouseholdMsg;
use App\HouseholdHouseMsg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use DB;
use Validator;
use App\Config;

class HouseholdManageController extends Controller
{
    /**
     * 进入新增住户信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AddHouseholdView()
    {
        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();
        $addresses = null;
        if (sizeof($areas) > 0) {
            $addresses = Config::select('id','name')
                ->where('parent_id', '=', $areas[0]->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('option.AddHouseholdView',
            [
                'active' => 'AddHouseholdView',
                'areas' => $areas,
                'addresses' => $addresses
            ]);
    }

    /**
     * 进入住户信息列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function HouseholdListView()
    {

        $householdmsgs = HouseholdMsg::orderBy('created_at','asc')->get();

        return view('option.HouseholdListView',
            [
                'active' => 'HouseholdListView',
                'householdMsgs' => $householdmsgs
            ]);
    }

    /**
     * 进入编辑用户信息页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editHouseholdMsg($id)
    {
        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();

        $householdmsg = HouseholdMsg::where('id', '=', $id)->first();

        $rents = $householdmsg->householdHouseMsg()
            ->where('is_check_out', '=', 0)
            ->orderBy('order', 'asc')
            ->get();


        $rentsCheckOut = $householdmsg->householdHouseMsg()
            ->where('is_check_out', '=', 1)
            ->orderBy('order', 'asc')
            ->get();

        return view('option.EditHouseholdView',
            [
                'active' => 'HouseholdListView',
                'householdMsg' => $householdmsg,
                'rents' => $rents,
                'rentsCheckOut' => $rentsCheckOut,
                'areas' => $areas
            ]);
    }

    /**
     * 获取所有区域和第一个区域的房址
     * @return mixed
     */
    public function getAreaAndAddress()
    {
        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();
        $addresses = null;
        if (sizeof($areas) > 0) {
            $addresses = Config::select('name')
                ->where('parent_id', '=', $areas[0]->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return response()->json(
            [
                'areas' => $areas,
                'addresses' => $addresses
            ]);
    }

    /**
     * 通过区域名获取房址
     * @param $name
     * @return mixed
     */
    public function getAddressByArea($id){
        $addresses = Config::select('id','name')
            ->where('parent_id', '=', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($addresses);
    }

    /**
     * 新增住户信息
     * @param Request $request
     */
    public function addHousehold(Request $request)
    {
        $data = Input::All();
        //将二维数组转化成一维数组
        if (\App\libraries\Util\what_kind_of_array($data['baseData']) == 2) {
            $baseData = \App\libraries\Util\array_two_to_one($data['baseData']);
        } else {
            $baseData = $data['baseData'];
        }
        //验证规则
        $rule = array(
            'name' => 'required|between:1,10',
            'jobNumber' => 'required|size:12',
            'cardNumber' => 'required|size:19',
            'hasHouse' => 'required|numeric|between:0,2',
            'hasHouseTime' => 'required|date',
            'type' => 'required|numeric|between:0,3'
        );

        $householdMsg = new HouseholdMsg();
        if (!Validator::make($baseData, $rule)->fails()) {
            $householdMsg->name = $baseData['name'];
            $householdMsg->job_number = $baseData['jobNumber'];
            $householdMsg->card_number = $baseData['cardNumber'];
            $householdMsg->has_house = $baseData['hasHouse'];
            $householdMsg->type = $baseData['type'];
            //判断是存入有房时间还是无房时间
            if ($baseData['hasHouse'] == 0) {
                $householdMsg->has_not_house_time = $baseData['hasHouseTime'];
            } else {
                $householdMsg->has_house_time = $baseData['hasHouseTime'];
            }
            //判断是否离职，若离职则存入离职时间
            if (isset($baseData['isDimission'])) {
                $householdMsg->is_dimission = 1;
                if (!Validator::make(
                    ['dimissionTime' => $baseData['dimissionTime']],
                    ['dimissionTime' => 'required|date'])->fails()
                ) {
                    $householdMsg->dimission_time = $baseData['dimissionTime'];
                } else {
                    return response()->json('baseMsgError');
                }
            } else {
                $householdMsg->is_dimission = 0;
            }

        } else {
            return response()->json('baseMsgError');
        }

        $rentArr = $data['rentArr'];

        //验证规则
        $rule = array(
            'region' => 'required|numeric',
            'address' => 'required|numeric',
            'area' => 'required|numeric',
            'firsttimeCheckIn' => 'required|date'
        );

        foreach ($rentArr as $rent) {
            $rent = \App\libraries\Util\array_two_to_one($rent);
            if (Validator::make($rent, $rule)->fails()) {
                return response()->json('rentMsgError');
            }
        }
        $householdMsg->save();
        $resultId = $householdMsg->id;
        for ($i = 0; $i < sizeof($rentArr); $i++) {
            $householdHouseMsg = new HouseholdHouseMsg();
            $householdHouseMsg->region_id = $rentArr[$i][0]['value'];
            $householdHouseMsg->address_id = $rentArr[$i][1]['value'];
            $householdHouseMsg->area = $rentArr[$i][2]['value'];
            $householdHouseMsg->firsttime_check_in = $rentArr[$i][3]['value'];
            $householdHouseMsg->order = $i + 1;
            $householdHouseMsg->household_id = $resultId;
            $householdHouseMsg->save();
        }

        return response()->json('success');
    }

    /**
     * 退房
     * @param $id
     * @return mixed
     */
    public function checkOutRent($id)
    {
        $result = HouseholdHouseMsg::where('id', '=', $id)
            ->update(['is_check_out' => 1]);
        if ($result == 1) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }

    /**
     * 新增租房
     * @param Request $request
     * @return mixed
     */
    public function addSingleRent(Request $request)
    {

        $data = Input::all();
        $householdId = $data['id'];
        $order = $data['order'];
        $input = $data['data'];

        //验证规则
        $rule = array(
            'region' => 'required|numeric',
            'address' => 'required|numeric',
            'area' => 'required|numeric',
            'firsttimeCheckIn' => 'required|date'
        );

        $input = \App\libraries\Util\array_two_to_one($input);
        if (!Validator::make($input, $rule)->fails()) {
            $householdHouseMsg = new HouseholdHouseMsg();
            $householdHouseMsg->region_id = $input['region'];
            $householdHouseMsg->address_id = $input['address'];
            $householdHouseMsg->area = $input['area'];
            $householdHouseMsg->firsttime_check_in = $input['firsttimeCheckIn'];
            $householdHouseMsg->order = $order;
            $householdHouseMsg->household_id = $householdId;
            $householdHouseMsg->save();
            return response()->json('success');
        } else {
            return response()->json('failed');
        }

    }

    /**
     * 租房作废，不记录房租信息
     * @param $id
     * @return mixed
     */
    public function deleteRent($id)
    {
        $result = HouseholdHouseMsg::destroy($id);
        if ($result == 1) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }

    /**
     * 保存住户基本信息的修改
     * @param Request $request
     * @return mixed
     */
    public function saveChange(Request $request){
        $flag = false;
        $data = Input::all();
        $householdId = $data['id'];
        $input = $data['data'];
        $input = \App\libraries\Util\array_two_to_one($input);
        //验证规则
        $rule = array(
            'name' => 'required|between:1,10',
            'jobNumber' => 'required|size:12',
            'cardNumber' => 'required|size:19',
            'hasHouse' => 'required|numeric|between:0,2',
            'hasHouseTime' => 'required|date',
            'type' => 'required|numeric|between:0,3'
        );

        $householdMsg = HouseholdMsg::find($householdId);

        if (!Validator::make($input, $rule)->fails()) {
            $householdMsg->name = $input['name'];
            $householdMsg->job_number = $input['jobNumber'];
            $householdMsg->card_number = $input['cardNumber'];
            $householdMsg->type = $input['type'];

            if ($householdMsg->has_house != $input['hasHouse']) {
                //重新统计房租
                $flag = true;
            }
            $householdMsg->has_house = $input['hasHouse'];
            //判断是存入有房时间还是无房时间
            if ($input['hasHouse'] == 0) {
                $householdMsg->has_not_house_time = $input['hasHouseTime'];
            } else {
                $householdMsg->has_house_time = $input['hasHouseTime'];
            }
            //判断是否离职，若离职则存入离职时间
            if (isset($input['isDimission'])) {
                if ($householdMsg->is_dimission == 0) {
                    //重新统计房租
                    $flag = true;
                }
                $householdMsg->is_dimission = 1;
                if (!Validator::make(
                    ['dimissionTime' => $input['dimissionTime']],
                    ['dimissionTime' => 'required|date'])->fails()
                ) {
                    $householdMsg->dimission_time = $input['dimissionTime'];
                } else {
                    return response()->json('failed');
                }
            } else {
                if ($householdMsg->is_dimission == 1) {
                    //重新统计房租
                    $flag = true;
                }
                $householdMsg->is_dimission = 0;
            }
            if ($flag) {
                //重新统计房租
            }
            $result = $householdMsg->save();
            if ($result == 1) {
                return response()->json('success');
            } else {
                return response()->json('failed');
            }

        } else {
            return response()->json('failed');
        }
    }

    /**
     * 删除住户信息
     * @param $id
     * @return mixed
     */
    public function deleteHouseholdMsg($id)
    {
        $householdMsg = HouseholdMsg::find($id);
        $result1 = HouseholdHouseMsg::where('household_id', '=', $householdMsg->id)
            ->delete();
        if ($result1 > 0) {
            $result2 = $householdMsg->delete();
            if ($result2 == 1) {
                return response()->json('success');
            } else {
                return response()->json('failed');
            }
        } else {
            return response()->json('failed');
        }
    }
}