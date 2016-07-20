<?php

namespace App\Http\Controllers\Admin;

use App\HouseholdMsg;
use App\HouseholdHouseMsg;
use App\Http\Controllers\Controller;
use App\Rent;
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

        //测试用的
//        date_default_timezone_set('PRC');
//        $now = time();
//        $time = strtotime('2014-06-25');
//        $intervel = $now - $time;
//        $year = date('Y', $intervel);
//        $month = date('m', $intervel);
//        $day = date('d', $intervel);
//        $days = date('t', $time);
//        $days = (int)($intervel / (24 * 60 * 60));
//        $year = (int)($days / 365);
//        $yushu = $days % 365;
//        $household = HouseholdMsg::where('id', 23)->first();
//        $time = $household->in_school_time;
//        $time = strtotime('2016-06-26 23:50:50');
//        $days = date('t', $time);
//        \App\libraries\Util\calculateOneMonthRent($household, $time, $days);

        //获取所有区域
        $areas = Config::select('id', 'name')
            ->where('parent_id', '=', '0')
            ->orderBy('created_at', 'asc')
            ->get();
        $addresses = null;
        if (sizeof($areas) > 0) {
            $addresses = Config::select('id', 'name')
                ->where('parent_id', '=', $areas[0]->id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('option.AddHouseholdView',
            [
                'active' => 'AddHouseholdView',
                'areas' => $areas,
                'addresses' => $addresses,
                'sildedown' => 'household'
            ]);
    }

    /**
     * 进入住户信息列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function HouseholdListView()
    {

        $input = Input::all();
        if (sizeof($input) == 0) {
            $input = [
                'jobNumber' => '',
                'name' => '',
                'institution' => '',
                'hasHouse' => '-1',
                'isDimission' => '-1'
            ];
            $householdmsgs = HouseholdMsg::orderBy('created_at', 'asc')->paginate(15);
        } else {
            $where = array();
            if ($input['hasHouse'] != -1) {
                $where['has_house'] = $input['hasHouse'];
            }
            if ($input['isDimission'] != -1) {
                $where['is_dimission'] = $input['isDimission'];
            }
            $householdmsgs = HouseholdMsg::where('job_number', 'like', '%' . $input['jobNumber'] . '%')
                ->where('name', 'like', '%' . $input['name'] . '%')
                ->where('institution', 'like', '%' . $input['institution'] . '%')
                ->where($where)
                ->orderBy('created_at', 'asc')
                ->paginate(15);
        }


        return view('option.HouseholdListView',
            [
                'active' => 'HouseholdListView',
                'householdMsgs' => $householdmsgs,
                'input' => $input,
                'sildedown' => 'household'
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

        //测试用的
//        date_default_timezone_set('PRC');
//        $time = time();
////        $time = strtotime('2016-01-31');
//        $days = date('t', $time);
//        \App\libraries\Util\calculateOneMonthRent($householdmsg, $time, $days);

//        foreach ($rents as $rent){
//            \App\libraries\Util\calculateAllMonthRent($householdmsg,$rent);
//        }

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
                'areas' => $areas,
                'sildedown' => 'household'
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
            $addresses = Config::select('id', 'name')
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
    public function getAddressByArea($id)
    {
        $addresses = Config::select('id', 'name')
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
            'jobNumber' => 'required|between:1,12|unique:household_msg,job_number',
            'cardNumber' => 'between:1,19',
            'institution' => 'required|between:1,20',
            'hasHouse' => 'required|numeric|between:0,2',
            'type' => 'required|numeric|between:0,3',
            'inSchoolTime' => 'required|date'
        );

        $householdMsg = new HouseholdMsg();
        if (!Validator::make($baseData, $rule)->fails()) {

            $noInputCountTime = false;//标志手动输入的累计时间是否为空
            if($baseData['inputCountTime'] == null){
                $noInputCountTime = true;
                $baseData['inputCountTime'] = '0.0.0';
            }

            $householdMsg->name = $baseData['name'];
            $householdMsg->job_number = $baseData['jobNumber'];
            $householdMsg->card_number = $baseData['cardNumber'];
            $householdMsg->institution = $baseData['institution'];
            $householdMsg->has_house = $baseData['hasHouse'];
            $householdMsg->type = $baseData['type'];
            $householdMsg->input_count_time = $baseData['inputCountTime'];
            $householdMsg->in_school_time = $baseData['inSchoolTime'];

            //判断是否离职
            if (isset($baseData['isDimission'])) {
                $householdMsg->is_dimission = 1;
            } else {
                $householdMsg->is_dimission = 0;
            }

            //判断是否无房改+补贴
            if (isset($baseData['hasHouseOrSubsidy'])) {
                $householdMsg->has_house_or_subsidy = 1;
            } else {
                $householdMsg->has_house_or_subsidy = 0;
            }

        } else {
            return response()->json('baseMsgError');
        }

        if (\App\libraries\Util\what_kind_of_array($data['rentData']) == 2) {
            $rentData = \App\libraries\Util\array_two_to_one($data['rentData']);
        } else {
            $rentData = $data['rentData'];
        }

        //验证规则
        $rule = array(
            'region' => 'required|numeric',
            'address' => 'required|numeric',
            'area' => 'required|numeric',
            'firsttimeCheckIn' => 'required|date',
        );

        if (Validator::make($rentData, $rule)->fails()) {
            return response()->json('rentMsgError');
        }

        if($noInputCountTime){
            //累计住房时间若是为空，则直接用第一次入住时间去计算累计时间
            $now_tmp = time();
            $intervel = $now_tmp - strtotime($rentData['firsttimeCheckIn']);
            if ($intervel >= 0) {
                $days_inc = (int)($intervel / (24 * 60 * 60));
                $householdMsg->incre_count_time = $days_inc;
            }
        }

        //比较现在的时间和租房第一次入住时间，大的那个作为“有房时间点”
        $now = time();
        $householdMsg->time_point = ($now >= strtotime($rentData['firsttimeCheckIn']) ? date('Y-m-d H:i:s', $now) : $rentData['firsttimeCheckIn']);
        $householdMsg->save();

        $resultId = $householdMsg->id;
        $householdHouseMsg = new HouseholdHouseMsg();
        $householdHouseMsg->region_id = $rentData['region'];
        $householdHouseMsg->address_id = $rentData['address'];
        $householdHouseMsg->area = $rentData['area'];
        $householdHouseMsg->firsttime_check_in = $rentData['firsttimeCheckIn'];
        $householdHouseMsg->room_number = $rentData['roomNumber'];
        $householdHouseMsg->remark = $rentData['remark'];
        $householdHouseMsg->order = 1;
        $householdHouseMsg->household_id = $resultId;
        $householdHouseMsg->save();

        \App\libraries\Util\calculateLastOneMonthRent($householdMsg, $householdHouseMsg);

        return response()->json('success');
    }

    /**
     * 退房
     * @param $id
     * @return mixed
     */
    public function checkOutRent($id)
    {
        date_default_timezone_set('PRC');
        $time = time();
//        $time = strtotime('2017-04-15 12:00:00');
        $days = date('t', $time);

        $rent = HouseholdHouseMsg::where('id', '=', $id)
            ->first();
        $householdmsg = HouseholdMsg::where('id', '=', $rent->household_id)
            ->first();
        \App\libraries\Util\calculateOneMonthOneRent($householdmsg, $rent, $time, $days);

        $result = HouseholdHouseMsg::where('id', '=', $id)
            ->update(['is_check_out' => 1]);
        if ($result == 1) {

            if ($rent->order == 1) {//退的是第一间房的情况下
                $rents = HouseholdHouseMsg::where('household_id', '=', $rent->household_id)
                    ->where('is_check_out', '=', 0)
                    ->orderBy('order', 'asc')
                    ->get();

                //若有两间租房，退掉第一间后，第二间自动变成第一间
                if (sizeof($rents) == 1) {
                    $rents[0]->order = 1;
                    $rents[0]->save();
                    //比较现在的时间和第二间租房第一次入住时间，大的那个作为“有房时间点”
                    $now = time();
                    $householdmsg->time_point = ($now >= strtotime($rents[0]->firsttime_check_in) ? date('Y-m-d H:i:s', $now) : $rents[0]->firsttime_check_in);
                    $householdmsg->save();
                }
            }


            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }

    /**
     * 新增一间租房
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
            'firsttimeCheckIn' => 'required|date',
        );

        $input = \App\libraries\Util\array_two_to_one($input);
        if (!Validator::make($input, $rule)->fails()) {

            //比较现在的时间和租房第一次入住时间，大的那个作为“有房时间点”
            $householdMsg = HouseholdMsg::where('id',$householdId)->first();
            $now = time();
//            $now = strtotime('2017-08-23 12:00:00');
            $householdMsg->time_point = ($now >= strtotime($input['firsttimeCheckIn']) ? date('Y-m-d H:i:s', $now) : $input['firsttimeCheckIn']);
            $householdMsg->save();

            $householdHouseMsg = new HouseholdHouseMsg();
            $householdHouseMsg->region_id = $input['region'];
            $householdHouseMsg->address_id = $input['address'];
            $householdHouseMsg->area = $input['area'];
            $householdHouseMsg->firsttime_check_in = $input['firsttimeCheckIn'];
            $householdHouseMsg->room_number = $input['roomNumber'];
            $householdHouseMsg->remark = $input['remark'];
            $householdHouseMsg->order = $order;
            $householdHouseMsg->household_id = $householdId;
            $householdHouseMsg->save();

            //计算房租
            $householdmsg = HouseholdMsg::where('id', '=', $householdId)
                ->first();
            \App\libraries\Util\calculateLastOneMonthRent($householdmsg, $householdHouseMsg);

            return response()->json('success');
        } else {
            return response()->json('failed');
        }

    }

    /**
     * 租房作废，不记录房租信息，同时删除与此租房有关的所有历史房租信息
     * @param $id
     * @return mixed
     */
    public function deleteRent($id)
    {
        //获取租房id
        $houseHoldHouse = HouseholdHouseMsg::where('id',$id)->first();
        $houseHoldHouseId = $houseHoldHouse->id;
        //删除租房
        $deleteHouse = HouseholdHouseMsg::destroy($id);
        //根据
        Rent::where('household_house_id',$houseHoldHouseId)
            ->delete();
        if ($deleteHouse == 1) {
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
    public function saveChange(Request $request)
    {
        $flag = false;
        $data = Input::all();
        $householdId = $data['id'];
        $input = $data['data'];
        $input = \App\libraries\Util\array_two_to_one($input);
        //验证规则
        $rule = array(
            'name' => 'required|between:1,10',
            'cardNumber' => 'between:1,19',
            'institution' => 'required|between:1,20',
            'hasHouse' => 'required|numeric|between:0,2',
            'type' => 'required|numeric|between:0,3',
        );

        $householdMsg = HouseholdMsg::find($householdId);
        $oldHouseholdMsg = HouseholdMsg::find($householdId);

        if (!Validator::make($input, $rule)->fails()) {
            $householdMsg->name = $input['name'];

            if ($householdMsg->job_number != $input['jobNumber']) {
                if (!Validator::make(
                    ['jobNumber' => $input['jobNumber']],
                    ['jobNumber' => 'required|between:1,12|unique:household_msg,job_number',])->fails()
                ) {
                    $householdMsg->job_number = $input['jobNumber'];
                } else {
                    return response()->json('failed');
                }
            }
            $householdMsg->card_number = $input['cardNumber'];
            $householdMsg->institution = $input['institution'];

            if($householdMsg->type == 3 && $input['type'] != 3){
                //重新统计房租
                $flag = true;
                $householdMsg->type = $input['type'];
            }else if($householdMsg->type != 3 && $input['type'] == 3){
                //重新统计房租
                $flag = true;
                $householdMsg->type = $input['type'];
            }else if($householdMsg->type >= 0 && $householdMsg->type <= 2 && $input['type'] >= 0 && $input['type'] <= 2){
                $householdMsg->type = $input['type'];
            }

            $householdMsg->input_count_time = $input['inputCountTime'];

            if ($householdMsg->has_house != $input['hasHouse']) {
                //重新统计房租
                $flag = true;
            }
            $householdMsg->has_house = $input['hasHouse'];
            //判断是否离职，若离职则存入离职时间
            if (isset($input['isDimission'])) {
                if ($householdMsg->is_dimission == 0) {
                    //重新统计房租
                    $flag = true;
                }
                $householdMsg->is_dimission = 1;

            } else {
                if ($householdMsg->is_dimission == 1) {
                    //重新统计房租
                    $flag = true;
                }
                $householdMsg->is_dimission = 0;
            }

            //判断是否无房改+补贴
            if (isset($input['hasHouseOrSubsidy'])) {
                if ($householdMsg->has_house_or_subsidy == 0) {
                    if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')){
                        //重新统计房租
                        $flag = true;
                    }
                }
                $householdMsg->has_house_or_subsidy = 1;

            } else {
                if ($householdMsg->has_house_or_subsidy == 1) {
                    if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')){
                        //重新统计房租
                        $flag = true;
                    }
                }
                $householdMsg->has_house_or_subsidy = 0;
            }

            if ($flag) {
                //重新统计房租
                $rent = $oldHouseholdMsg->Rent()->orderBy('time_pay_rent', 'desc')->first();
                date_default_timezone_set('PRC');
                $time = time();
                $days = date('t', $time);
                //最新交租时间与现在一样的时候不插入房租信息
                $mm = date('Y-m-d', strtotime($rent->time_pay_rent));
                $nn = date('Y-m-d', $time);
                if (date('Y-m-d', strtotime($rent->time_pay_rent)) != date('Y-m-d', $time)) {
                    \App\libraries\Util\calculateOneMonthRent($oldHouseholdMsg, $time, $days);
                }
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
        Rent::where('household_id', '=', $householdMsg->id)
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

    /**
     * 保存房租修改信息
     *
     * @param $id
     * @param $order
     * @return mixed
     */
    public function saveRentMsg($id, $order)
    {
        $rentTmp = HouseholdHouseMsg::where('id', $id)->first();
        $householdId = $rentTmp->household_id;
        $FirstRent = HouseholdHouseMsg::where('household_id', $householdId)
            ->where('order',1)
            ->where('is_check_out', '=', 0)
            ->first();

        //已经存在第一间房则不允许将其他房间设置成第一间房
        if($order == 1 && $FirstRent != null){
            return response()->json('failed');
        }

        $rents = HouseholdHouseMsg::where('household_id', $householdId)
            ->where('is_check_out', '=', 0)
            ->get();


        if($order == 1 && $FirstRent == null){
            $householdmsg = HouseholdMsg::where('id',$householdId)->first();
            $now = time();
            if(strtotime($rents[0]->firsttime_check_in)>=strtotime($rents[1]->firsttime_check_in)){
                $max = strtotime($rents[0]->firsttime_check_in);
                $min = strtotime($rents[1]->firsttime_check_in);
            }else{
                $max = strtotime($rents[1]->firsttime_check_in);
                $min = strtotime($rents[0]->firsttime_check_in);
            }

            //若现在的时间大于两间房的第一次入住时间，则“有房时间点”选现在的时间
            if($now >= $max){
                $householdmsg->time_point = date('Y-m-d H:i:s', $now);
            }

            //若现在的时间小于两间房的第一次入住时间，则“有房时间点”选两间房中第一次入住时间小的那个时间
            if($now <= $min){
                $householdmsg->time_point = date('Y-m-d 00:00:00', $min);
            }

            //若现在的时间位于两间房第一次入住时间之间，则“有房时间点”选现在的时间
            if($now >= $min && $now <= $max){
                $householdmsg->time_point = date('Y-m-d H:i:s', $now);
            }
            $householdmsg->save();
        }



        $rentTmp->order = $order;
        $rentTmp->save();

        return response()->json('success');

    }
}