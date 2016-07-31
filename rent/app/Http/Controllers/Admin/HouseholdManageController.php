<?php

namespace App\Http\Controllers\Admin;

use App\HouseholdHouseRelation;
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
     * 进入新增合租住户信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AddShareHouseholdView()
    {
        return view('option.AddShareHouseholdView',
            [
                'active' => 'AddShareHouseholdView',
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

        $householdHouseRelations = $householdmsg->householdHouseRelation()
            ->get();

        $householdHouseRelationsUnCheck = array();
        $householdHouseRelationsCheckOut = array();

        $unCheckIndex = 0;
        $CheckIndex = 0;
        for($i = 0 ; $i < sizeof($householdHouseRelations); $i++){
            if($householdHouseRelations[$i]->status == 1 || $householdHouseRelations[$i]->status == 2){
                $householdHouseRelationsUnCheck[$unCheckIndex++] = $householdHouseRelations[$i];
            }
            if($householdHouseRelations[$i]->status == 3 || $householdHouseRelations[$i]->status == 4){
                $householdHouseRelationsCheckOut[$CheckIndex++] = $householdHouseRelations[$i];
            }
        }

        //合租的，则获取另一位合租人信息
        $anoHousehold = null;
        if(sizeof($householdHouseRelationsUnCheck) == 1 && $householdHouseRelationsUnCheck[0]->status == 2){
            $anoHouseholdHouseRelation = HouseholdHouseRelation::where('household_house_id',$householdHouseRelationsUnCheck[0]->household_house_id)
                ->where('household_id','<>',$householdHouseRelationsUnCheck[0]->household_id)
                ->where('status',2)
                ->first();
            $anoHousehold = HouseholdMsg::where('id',$anoHouseholdHouseRelation->household_id)->first();
        }

        //测试用的
//        date_default_timezone_set('PRC');
//        $time = time();
////        $time = strtotime('2016-01-31');
//        $days = date('t', $time);
//        \App\libraries\Util\calculateOneMonthRent($householdmsg, $time, $days);

//        foreach ($rents as $rent){
//            \App\libraries\Util\calculateAllMonthRent($householdmsg,$rent);
//        }


        return view('option.EditHouseholdView',
            [
                'active' => 'HouseholdListView',
                'householdMsg' => $householdmsg,
                'householdHouseRelations' => $householdHouseRelationsUnCheck,
                'householdHouseRelationsCheckOut' => $householdHouseRelationsCheckOut,
                'anoHousehold' => $anoHousehold,
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
     * 验证住户的工号是否重复
     * @param $jobNumber
     * @return mixed
     */
    public function validateJobNumber()
    {
        $jobNumber = Input::All();
        //验证规则
        $rule = array(
            'jobNumber' => 'required|between:1,12|unique:household_msg,job_number'
        );
        if (!Validator::make($jobNumber, $rule)->fails()) {
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    /**
     * 新增住户信息，且住户单独拥有租房
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
            if ($baseData['inputCountTime'] == null) {
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

            //判断是否享受标租
            if (isset($baseData['privilege'])) {
                $householdMsg->privilege = 1;
            } else {
                $householdMsg->privilege = 0;
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

        if ($noInputCountTime) {
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

        $householdMsgId = $householdMsg->id;
        $householdHouseMsg = new HouseholdHouseMsg();
        $householdHouseMsg->region_id = $rentData['region'];
        $householdHouseMsg->address_id = $rentData['address'];
        $householdHouseMsg->area = $rentData['area'];
        $householdHouseMsg->firsttime_check_in = $rentData['firsttimeCheckIn'];
        $householdHouseMsg->room_number = $rentData['roomNumber'];
        $householdHouseMsg->remark = $rentData['remark'];
        $householdHouseMsg->order = 1;
        $householdHouseMsg->save();

        $householdHouseMsgId = $householdHouseMsg->id;

        $householdHouseRelation = new HouseholdHouseRelation();
        $householdHouseRelation->status = 1;
        $householdHouseRelation->household_id = $householdMsgId;
        $householdHouseRelation->household_house_id = $householdHouseMsgId;
        $householdHouseRelation->save();

        \App\libraries\Util\calculateLastOneMonthRent($householdMsg, $householdHouseMsg);

        return response()->json('success');
    }


    /**
     * 新增合租人
     * @param Request $request
     * @return mixed
     */
    public function addShareHousehold(Request $request)
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
            'institution' => 'required|between:1,20'
        );

        $householdMsg = new HouseholdMsg();
        //获取另一位合租人
        $anotherHouseholdMsg = HouseholdMsg::where('id',$data['householdId'])->first();

        if (!Validator::make($baseData, $rule)->fails()) {
            $householdMsg->name = $baseData['name'];
            $householdMsg->job_number = $baseData['jobNumber'];
            $householdMsg->card_number = $baseData['cardNumber'];
            $householdMsg->institution = $baseData['institution'];
            $householdMsg->has_house = $anotherHouseholdMsg->has_house;
            $householdMsg->is_dimission = $anotherHouseholdMsg->is_dimission;
            $householdMsg->type = $anotherHouseholdMsg->type;
            $householdMsg->input_count_time = $anotherHouseholdMsg->input_count_time;
            $householdMsg->incre_count_time = $anotherHouseholdMsg->incre_count_time;
            $householdMsg->in_school_time = $anotherHouseholdMsg->in_school_time;
            $householdMsg->has_house_or_subsidy = $anotherHouseholdMsg->has_house_or_subsidy;
            $householdMsg->time_point = $anotherHouseholdMsg->time_point;

        } else {
            return response()->json('baseMsgError');
        }

        $householdMsg->save();
        //获取另一位合租人的租房
        $anotherHouseholdHouseMsg = $anotherHouseholdMsg->householdHouseRelation()
            ->where('status',1)
            ->first()
            ->householdHouseMsg()->first();
        //获取另一位合租人上个月的房租
        $rent = Rent::where('household_id',$anotherHouseholdMsg->id)
            ->where('household_house_id',$anotherHouseholdHouseMsg->id)
            ->orderBy('created_at','desc')
            ->first();
        if($rent != null){
            //另一位合租人上个月房租减半
            $rent->rent = $rent->rent/2;
            $rent->save();

            //录入新增合租人上个月的房租
            $newRent = new Rent();
            $newRent->firsttime_check_in = $rent->firsttime_check_in;
            $newRent->lasttime_pay_rent = $rent->lasttime_pay_rent;
            $newRent->time_pay_rent = $rent->time_pay_rent;
            $newRent->rent = $rent->rent;
            $newRent->intervel = $rent->intervel;
            $newRent->isDimission = $rent->isDimission;
            $newRent->order = $rent->order;
            $newRent->hasHouse = $rent->hasHouse;
            $newRent->time = $rent->time;
            $newRent->region = $rent->region;
            $newRent->address = $rent->address;
            $newRent->room_number = $rent->room_number;
            $newRent->money = $rent->money;
            $newRent->area = $rent->area;
            $newRent->household_id = $householdMsg->id;
            $newRent->household_house_id = $anotherHouseholdHouseMsg->id;
            $newRent->save();
        }


        //改变另一位合租人对租房的状态
        $anoHouseholdHouseRelation = $anotherHouseholdMsg->householdHouseRelation()
            ->where('status',1)
            ->first();
        $anoHouseholdHouseRelation->status = 2;
        $anoHouseholdHouseRelation->save();

        //为新增合租人添加住户租房关联信息
        $newHouseholdHouseRelation = new HouseholdHouseRelation();
        $newHouseholdHouseRelation->status = 2;
        $newHouseholdHouseRelation->household_id = $householdMsg->id;
        $newHouseholdHouseRelation->household_house_id = $anotherHouseholdHouseMsg->id;
        $newHouseholdHouseRelation->save();

        return response()->json('success');
    }

    /**
     * 退房
     * @param $id
     * @return mixed
     */
    public function checkOutRent($householdId, $householdHouseId)
    {
        date_default_timezone_set('PRC');
        $time = time();
//        $time = strtotime('2017-04-15 12:00:00');
        $days = date('t', $time);

        $householdHouseMsg = HouseholdHouseMsg::where('id', '=', $householdHouseId)
            ->first();
        $householdHouseRelation = $householdHouseMsg->householdHouseRelation()
            ->where('household_id', '=', $householdId)
            ->where('household_house_id', '=', $householdHouseId)
            ->first();
        $householdmsg = HouseholdMsg::where('id', '=', $householdId)->first();

        if ($householdHouseRelation->status == 1) {//单租的情况
            \App\libraries\Util\calculateOneMonthOneRent($householdmsg, $householdHouseMsg, $time, $days);
            $householdHouseRelation->status = 4;
            $result = $householdHouseRelation->save();
            if ($result == 1) {

                if ($householdHouseMsg->order == 1) {//退的是第一间房的情况下
                    $householdHouseRelations = $householdmsg->householdHouseRelation()
                        ->where('status', '=', 1)
                        ->get();
                    $rents = array();
                    for($i = 0 ; $i < sizeof($householdHouseRelations) ; $i++){
                        $rents[$i] = $householdHouseRelations[$i]->householdHouseMsg()->first();
                    }

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
        } else if ($householdHouseRelation->status == 2) {//合租的情况

            //先算退房的人的房租
            \App\libraries\Util\calculateOneMonthOneHalfRent($householdmsg, $householdHouseMsg, $time, $days);
            //改变退房人对租房的状态
            $householdHouseRelation->status = 3;
            $householdHouseRelation->save();
            //为另一位合租人的新增房租
            $rent = Rent::where('household_id', $householdId)
                ->where('household_house_id', $householdHouseId)
                ->orderBy('created_at', 'desc')
                ->first();
            $anoHouseholdHouseRelation = HouseholdHouseRelation::where('household_house_id',$householdHouseMsg->id)
                ->where('household_id','<>',$householdId)
                ->where('status',2)
                ->first();
            $anoHouseholdMsg = $anoHouseholdHouseRelation->householdMsg()->first();
            $newRent = new Rent();
            $newRent->firsttime_check_in = $rent->firsttime_check_in;
            $newRent->lasttime_pay_rent = $rent->lasttime_pay_rent;
            $newRent->time_pay_rent = $rent->time_pay_rent;
            $newRent->rent = $rent->rent;
            $newRent->intervel = $rent->intervel;
            $newRent->isDimission = $rent->isDimission;
            $newRent->order = $rent->order;
            $newRent->hasHouse = $rent->hasHouse;
            $newRent->time = $rent->time;
            $newRent->region = $rent->region;
            $newRent->address = $rent->address;
            $newRent->room_number = $rent->room_number;
            $newRent->money = $rent->money;
            $newRent->area = $rent->area;
            $newRent->household_id = $anoHouseholdMsg->id;
            $newRent->household_house_id = $rent->household_house_id;
            $newRent->save();
            //改变另一位合租人对租房的状态
            $anoHouseholdHouseRelation->status = 1;
            $anoHouseholdHouseRelation->save();
        }

        return response()->json('success');

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
            $householdMsg = HouseholdMsg::where('id', $householdId)->first();
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
            $householdHouseMsg->save();

            $householdHouseRelation = new HouseholdHouseRelation();
            $householdHouseRelation->status = 1;
            $householdHouseRelation->household_id = $householdId;
            $householdHouseRelation->household_house_id = $householdHouseMsg->id;
            $householdHouseRelation->save();

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
    public function deleteRent($householdId, $householdHouseId)
    {
        //删除租房
        $deleteHouse = HouseholdHouseMsg::destroy($householdHouseId);
        //删除关联表数据
        HouseholdHouseRelation::where('household_id', $householdId)
            ->where('household_house_id', $householdHouseId)
            ->delete();
        //根据
        Rent::where('household_id', $householdId)
            ->where('household_house_id', $householdHouseId)
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

            if ($householdMsg->type == 3 && $input['type'] != 3) {
                //重新统计房租
                $flag = true;
                $householdMsg->type = $input['type'];
            } else if ($householdMsg->type != 3 && $input['type'] == 3) {
                //重新统计房租
                $flag = true;
                $householdMsg->type = $input['type'];
            } else if ($householdMsg->type >= 0 && $householdMsg->type <= 2 && $input['type'] >= 0 && $input['type'] <= 2) {
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
                    if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')) {
                        //重新统计房租
                        $flag = true;
                    }
                }
                $householdMsg->has_house_or_subsidy = 1;

            } else {
                if ($householdMsg->has_house_or_subsidy == 1) {
                    if (strtotime($householdMsg->in_school_time) <= strtotime('1999-12-31')) {
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
        $householdHouseRelations = $householdMsg->householdHouseRelation()->get();
        $result1 = 0;
        foreach ($householdHouseRelations as $householdHouseRelation) {
            if($householdHouseRelation->status != 3){
                $householdHouseRelation->householdHouseMsg()->first()->delete();
            }
            $result1 = $householdHouseRelation->delete();
        }
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
    public function saveRentMsg($householdId,$householdHouseId, $order)
    {
        $rentTmp = HouseholdHouseMsg::where('id', $householdHouseId)->first();
        $household = HouseholdMsg::where('id', $householdId)
            ->first();
        $rentsTmp = $household->householdHouseRelation()->where('status',1)->get();
        $FirstRent = null;
        foreach ($rentsTmp as $item){
            if($item->householdHouseMsg->order == 1){
                $FirstRent = $item->householdHouseMsg;
            }
        }
        //已经存在第一间房则不允许将其他房间设置成第一间房
        if ($order == 1 && $FirstRent != null) {
            return response()->json('failed');
        }

        $householdHouseRelations = $household->householdHouseRelation()
            ->where('status', '=', 1)
            ->get();
        $rents = array();
        for($i = 0 ; $i < sizeof($householdHouseRelations) ; $i++){
            $rents[$i] = $householdHouseRelations[$i]->householdHouseMsg()->first();
        }


        if ($order == 1 && $FirstRent == null) {
            $householdmsg = HouseholdMsg::where('id', $householdId)->first();
            $now = time();
            if (strtotime($rents[0]->firsttime_check_in) >= strtotime($rents[1]->firsttime_check_in)) {
                $max = strtotime($rents[0]->firsttime_check_in);
                $min = strtotime($rents[1]->firsttime_check_in);
            } else {
                $max = strtotime($rents[1]->firsttime_check_in);
                $min = strtotime($rents[0]->firsttime_check_in);
            }

            //若现在的时间大于两间房的第一次入住时间，则“有房时间点”选现在的时间
            if ($now >= $max) {
                $householdmsg->time_point = date('Y-m-d H:i:s', $now);
            }

            //若现在的时间小于两间房的第一次入住时间，则“有房时间点”选两间房中第一次入住时间小的那个时间
            if ($now <= $min) {
                $householdmsg->time_point = date('Y-m-d 00:00:00', $min);
            }

            //若现在的时间位于两间房第一次入住时间之间，则“有房时间点”选现在的时间
            if ($now >= $min && $now <= $max) {
                $householdmsg->time_point = date('Y-m-d H:i:s', $now);
            }
            $householdmsg->save();
        }


        $rentTmp->order = $order;
        $rentTmp->save();

        return response()->json('success');

    }


    /**
     * 查找合租住户
     *
     * @param Request $request
     * @return mixed
     */
    public function searchShareHousehold(Request $request){
        $name = $request->input('name');
        $jobNumber = $request->input('jobNumber');
        //根据姓名、工号寻找合租租户
        $household = HouseholdMsg::where('name',$name)
            ->where('job_number',$jobNumber)
            ->first();
        if($household == null){
            return response()->json('none');
        }

        $householdHouseRelation = $household->householdHouseRelation()->where('status',2)->get();
        //合租人已经和别人合租则不合格
        if(sizeof($householdHouseRelation) > 0){
            return response()->json('rented');
        }

        $householdHouseRelation = $household->householdHouseRelation()->where('status',1)->get();
        //合租人拥有的单间多于1或少于1都不合格
        if(sizeof($householdHouseRelation) != 1){
            return response()->json('illegal');
        }

        $rent = $householdHouseRelation[0]->householdHouseMsg;

        $rentMsg['householdId'] = $household->id;
        $rentMsg['region'] = $rent->regionMsg->name;
        $rentMsg['address'] = $rent->addressMsg->name;
        $rentMsg['firsttime_check_in'] = date('Y-m-d',strtotime($rent->firsttime_check_in));
        $rentMsg['area'] = $rent->area;
        $rentMsg['room_number'] = $rent->room_number;
        $rentMsg['remark'] = $rent->remark;

        return response()->json($rentMsg);

    }
}