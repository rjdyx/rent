<?php

namespace App\Http\Controllers\Admin;

use App\Config;
use App\HouseholdMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Rent;
use Excel;
use Validator;
use App\HouseholdHouseMsg;
use App\Jobs\ImportHouseholdMsg;


class ExcelController extends Controller
{

    /**
     * 进入导入导出页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function importExportView()
    {
        return view('option.ImportExportView', [
            'active' => 'importExportView',
            'sildedown' => 'importExportView'
        ]);
    }

    /**
     * 导出
     */
    public function export()
    {
        $input = Input::all();

        $all = array(['姓名', '工号', '月租金额']);//全部
        $school = array(['姓名', '工号', '月租金额']);//校发
        $province = array(['姓名', '账号', '月租金额']);//省发
        $lease = array(['姓名', '工号', '月租金额']);//租赁人员
        $postdoctor = array(['姓名', '工号', '月租金额']);//博士后


        $rentArr['allInput'] = 0;
        $rentArr['schoolInput'] = 0;
        $rentArr['provinceInput'] = 0;
        $rentArr['leaseInput'] = 0;
        $rentArr['postdoctorInput'] = 0;

        date_default_timezone_set('PRC');
        $BeginDate = date('Y-m-01', time());//获取月份的第一天
        $endTime = strtotime("$BeginDate -1 day");//获取上个月份的最后一天
        $BeginDate = date('Y-m-01 00:00:00', $endTime);//获取上个月的第一天

        if (isset($input['all'])) {
            $rentArr['allInput'] = 1;
            //需要做一下时间判断
            $householdMsgs = HouseholdMsg::all();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                $totalRent = 0;
                foreach ($rents as $rent) {
                    $totalRent += $rent->rent;
                }
                $tmp = array();
                $tmp[0] = $householdMsg->name;
                $tmp[1] = $householdMsg->job_number;
                $tmp[2] = $totalRent;
                array_push($all, $tmp);
            }
        }

        if (isset($input['school'])) {
            $rentArr['schoolInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 0)->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                $totalRent = 0;
                foreach ($rents as $rent) {
                    $totalRent += $rent->rent;
                }
                $tmp = array();
                $tmp[0] = $householdMsg->name;
                $tmp[1] = $householdMsg->job_number;
                $tmp[2] = $totalRent;
                array_push($school, $tmp);
            }
        }

        if (isset($input['province'])) {
            $rentArr['provinceInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 1)->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                $totalRent = 0;
                foreach ($rents as $rent) {
                    $totalRent += $rent->rent;
                }
                $tmp = array();
                $tmp[0] = $householdMsg->name;
                $tmp[1] = $householdMsg->job_number;
                $tmp[2] = $totalRent;
                array_push($province, $tmp);
            }
        }

        if (isset($input['lease'])) {
            $rentArr['leaseInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 2)
                ->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                $totalRent = 0;
                foreach ($rents as $rent) {
                    $totalRent += $rent->rent;
                }
                $tmp = array();
                $tmp[0] = $householdMsg->name;
                $tmp[1] = $householdMsg->job_number;
                $tmp[2] = $totalRent;
                array_push($lease, $tmp);
            }
        }

        if (isset($input['postdoctor'])) {
            $rentArr['postdoctorInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 3)
                ->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                $totalRent = 0;
                foreach ($rents as $rent) {
                    $totalRent += $rent->rent;
                }
                $tmp = array();
                $tmp[0] = $householdMsg->name;
                $tmp[1] = $householdMsg->job_number;
                $tmp[2] = $totalRent;
                array_push($postdoctor, $tmp);
            }
        }

        $rentArr['all'] = $all;
        $rentArr['school'] = $school;
        $rentArr['province'] = $province;
        $rentArr['lease'] = $lease;
        $rentArr['postdoctor'] = $postdoctor;

        Excel::create('房租', function ($excel) use ($rentArr) {
            $all = $rentArr['all'];
            $school = $rentArr['school'];
            $province = $rentArr['province'];
            $lease = $rentArr['lease'];
            $postdoctor = $rentArr['postdoctor'];
            $allInput = $rentArr['allInput'];
            $schoolInput = $rentArr['schoolInput'];
            $provinceInput = $rentArr['provinceInput'];
            $leaseInput = $rentArr['leaseInput'];
            $postdoctorInput = $rentArr['postdoctorInput'];

            if ($allInput == 1) {
                $excel->sheet('全部', function ($sheet) use ($all) {
                    $sheet->rows($all);
                });
            }

            if ($schoolInput == 1) {
                $excel->sheet('校发', function ($sheet) use ($school) {
                    $sheet->rows($school);
                });
            }
            if ($provinceInput == 1) {
                $excel->sheet('统发', function ($sheet) use ($province) {
                    $sheet->rows($province);
                });
            }
            if ($leaseInput == 1) {
                $excel->sheet('租赁人员', function ($sheet) use ($lease) {
                    $sheet->rows($lease);
                });
            }
            if ($postdoctorInput == 1) {
                $excel->sheet('博士后', function ($sheet) use ($postdoctor) {
                    $sheet->rows($postdoctor);
                });
            }
        })->export('xls');
    }

    /**
     * 导入
     * @param Request $request
     */
    public function import(Request $request)
    {
        $file = $request->file('file');
        if ($file->isValid()) {
            $path = 'upload/';
            $Extension = $file->getClientOriginalExtension();
            $filename = 'tmp.' . $Extension;
            $file->move($path, $filename);
            $filePath = 'upload/tmp.' . $Extension;

            Excel::load($filePath, function($reader) {
                //获取excel的第1张表
                $reader = $reader->getSheet(0);
                //获取表中的数据
                $results = $reader->toArray();

                //基本信息验证规则
                $baseRule = array(
                    'name' => 'required|between:1,10',
                    'jobNumber' => 'required|between:1,12|unique:household_msg,job_number',
                    'cardNumber' => 'between:1,19',
                    'institution' => 'required|between:1,20',
                    'hasHouse' => 'required|numeric|between:0,2',
                    'type' => 'required|numeric|between:0,3',
                    'inSchoolTime' => 'required|date'
                );

                //租房信息验证规则
                $rentRule = array(
                    'area' => 'required|numeric',
                    'firsttimeCheckIn' => 'required|date',
                );

                $tmpHouseholdMsg = null;//临时的HouseholdMsg，用于第二间租房的HouseholdMsg信息存储
                $tmpOrder = 0;//用于判断同一用户两个租房的order是否重复，而order是否是规定的1和2则由valid来验证
                $flag = false;//基本信息存储出错，则第二间租房也不能存储，用此来做标志
                $errorArr = array(['姓名', '工号', '银行卡号', '发放方式', '单位', '是否有房', '是否离职', '累计住房时间', '入校时间', '无房改+补贴', '区域', '房址', '房间号', '面积', '入住时间', '第几间房', '备注']);

                for ($i = 1; $i < sizeof($results); $i++) {


                    $householdMsg = new HouseholdMsg();
                    $msg = $results[$i];

                    //去掉全null的行
                    $allNull = false;
                    for ($m = 0; $m < sizeof($msg); $m++) {
                        if ($msg[$m] != null) {
                            $allNull = false;
                            break;
                        }
                        $allNull = true;
                    }
                    if ($allNull) {
                        continue;
                    }

                    if ($msg[0] == null || $msg[1] == null || $msg[2] == null || $msg[3] == null) {

                        //第一条出错：所有信息回收
                        if ($flag) {
                            //记录错误信息
                            array_push($errorArr, $msg);
//                            $flag = false;
                            continue;
                        }

                        //第一次入住时间为空，则直接使用入校时间
                        if($msg[14]){
                            $msg[14] = $tmpHouseholdMsg->in_school_time;
                        }

                        $arrRent['roomNumber'] = $msg[12];
                        $arrRent['area'] = $msg[13];
                        $arrRent['firsttimeCheckIn'] = $msg[14];
                        $arrRent['order'] = $msg[15];
                        $arrRent['remark'] = $msg[16];

                        //第一条正确，第二条验证出错，第三条到不了这里
                        if (Validator::make($arrRent, $rentRule)->fails()) {
                            //记录错误数据
                            $msg[0] = $tmpHouseholdMsg->name;
                            $msg[1] = $tmpHouseholdMsg->job_number;
                            $msg[2] = $tmpHouseholdMsg->card_number;
                            $msg[3] = $tmpHouseholdMsg->institution;
                            array_push($errorArr, $msg);
                            continue;
                        }
                        $region = Config::where('name', $msg[10])->first();

                        if (isset($region)) {
                            $arrRent['region'] = $region->id;
                            $address = Config::where('parent_id', $region->id)
                                ->where('name', $msg[11])
                                ->first();
                            if (isset($address)) {
                                $arrRent['address'] = $address->id;
                            } else {
                                //记录错误数据
                                $msg[0] = $tmpHouseholdMsg->name;
                                $msg[1] = $tmpHouseholdMsg->job_number;
                                $msg[2] = $tmpHouseholdMsg->card_number;
                                $msg[3] = $tmpHouseholdMsg->institution;
                                array_push($errorArr, $msg);
                                continue;
                            }
                        } else {
                            //记录错误信息
                            $msg[0] = $tmpHouseholdMsg->name;
                            $msg[1] = $tmpHouseholdMsg->job_number;
                            $msg[2] = $tmpHouseholdMsg->card_number;
                            $msg[3] = $tmpHouseholdMsg->institution;
                            array_push($errorArr, $msg);
                            continue;
                        }

                        $householdHouseMsg = new HouseholdHouseMsg();
                        $householdHouseMsg->region_id = $arrRent['region'];
                        $householdHouseMsg->address_id = $arrRent['address'];
                        $householdHouseMsg->area = $arrRent['area'];
                        $householdHouseMsg->firsttime_check_in = $arrRent['firsttimeCheckIn'];
                        $householdHouseMsg->order = $arrRent['order'];
                        $householdHouseMsg->room_number = $arrRent['roomNumber'];
                        $householdHouseMsg->remark = $arrRent['remark'];
                        $householdHouseMsg->household_id = $tmpHouseholdMsg->id;
                        $householdHouseMsg->save();

                        \App\libraries\Util\calculateLastOneMonthRent($tmpHouseholdMsg, $householdHouseMsg);

                    } else {
                        $flag = false;

                        $typeNum = 0;//发放方式
                        $hasRoomNum = 0;//是否有房
                        $isDimissionNum = 0;//是否离职
                        $hasHouseOrSubsidy = 0;//是否无房改+补贴

                        switch ($msg[3]) {
                            case "省发":
                                $typeNum = 0;
                                break;
                            case "校发":
                                $typeNum = 1;
                                break;
                            case "租赁人员":
                                $typeNum = 2;
                                break;
                            case "博士后":
                                $typeNum = 3;
                                break;
                            default:
                                $typeNum = 0;
                                break;
                        }

                        switch ($msg[5]) {
                            case "无房":
                                $hasRoomNum = 0;
                                break;
                            case "八区内有房":
                                $hasRoomNum = 1;
                                break;
                            case "有房改房":
                                $hasRoomNum = 2;
                                break;
                            default:
                                $hasRoomNum = 0;
                                break;
                        }

                        switch ($msg[6]) {
                            case "否":
                                $isDimissionNum = 0;
                                break;
                            case "是":
                                $isDimissionNum = 1;
                                break;
                            default:
                                $isDimissionNum = 0;
                                break;
                        }

                        switch ($msg[9]) {
                            case "无":
                                $hasHouseOrSubsidy = 0;
                                break;
                            case "有":
                                $hasHouseOrSubsidy = 1;
                                break;
                            default:
                                $hasHouseOrSubsidy = 0;
                                break;
                        }

                        $noInputCountTime = false;//标志手动输入的累计时间是否为空
                        if($msg[7] == null){
                            $msg[7] = '0.0.0';
                            $noInputCountTime = true;
                        }

                        $arrBase['name'] = $msg[0];//姓名
                        $arrBase['jobNumber'] = $msg[1];//工号
                        $arrBase['cardNumber'] = $msg[2];//银行卡号
                        $arrBase['type'] = $typeNum;//发放方式
                        $arrBase['institution'] = $msg[4];//单位
                        $arrBase['hasHouse'] = $hasRoomNum;//是否有房
                        $arrBase['isDimission'] = $isDimissionNum;//是否离职
                        $arrBase['inputCountTime'] = $msg[7];//累计住房时间
                        $arrBase['inSchoolTime'] = $msg[8];//入校时间
                        $arrBase['hasHouseOrSubsidy'] = $hasHouseOrSubsidy;//入校时间


                        if (!Validator::make($arrBase, $baseRule)->fails()) {
                            $householdMsg->name = $arrBase['name'];
                            $householdMsg->job_number = $arrBase['jobNumber'];
                            $householdMsg->card_number = $arrBase['cardNumber'];
                            $householdMsg->institution = $arrBase['institution'];
                            $householdMsg->has_house = $arrBase['hasHouse'];
                            $householdMsg->type = $arrBase['type'];
                            $householdMsg->input_count_time = $arrBase['inputCountTime'];
                            $householdMsg->in_school_time = $arrBase['inSchoolTime'];
                            $householdMsg->has_house_or_subsidy = $arrBase['hasHouseOrSubsidy'];
                            $householdMsg->is_dimission = $arrBase['isDimission'];
                            if($noInputCountTime){
                                //累计住房时间若是为空，则直接用入校时间去计算累计时间
                                $now_tmp = time();
                                $intervel = $now_tmp - strtotime($householdMsg->in_school_time);
                                if ($intervel >= 0) {
                                    $days_inc = (int)($intervel / (24 * 60 * 60));
                                    $householdMsg->incre_count_time += $days_inc;
                                    $householdMsg->time_point = date('Y-m-d', $now_tmp);
                                }
                                $noInputCountTime = false;
                            }
                        } else {
                            //记录错误信息
                            array_push($errorArr, $msg);
                            $flag = true;
                            continue;
                        }

                        //第一次入住时间为空，则直接使用入校时间
                        if($msg[14] == null){
                            $msg[14] = $msg[8];
                        }

                        $arrRent['roomNumber'] = $msg[12];
                        $arrRent['area'] = $msg[13];
                        $arrRent['firsttimeCheckIn'] = $msg[14];
                        $tmpOrder = $arrRent['order'] = $msg[15];
                        $arrRent['remark'] = $msg[16];

                        if (Validator::make($arrRent, $rentRule)->fails()) {
                            //记录错误数据
                            array_push($errorArr, $msg);
                            $flag = true;
                            continue;
                        }
                        $region = Config::where('name', $msg[10])->first();

                        if (isset($region)) {
                            $arrRent['region'] = $region->id;
                            $address = Config::where('parent_id', $region->id)
                                ->where('name', $msg[11])
                                ->first();
                            if (isset($address)) {
                                $arrRent['address'] = $address->id;
                            } else {
                                //记录错误数据
                                array_push($errorArr, $msg);
                                $flag = true;
                                continue;
                            }
                        } else {
                            //记录错误信息
                            array_push($errorArr, $msg);
                            $flag = true;
                            continue;
                        }


                        $householdMsg->save();
                        $tmpHouseholdMsg = $householdMsg;
                        $resultId = $householdMsg->id;
                        $householdHouseMsg = new HouseholdHouseMsg();
                        $householdHouseMsg->region_id = $arrRent['region'];
                        $householdHouseMsg->address_id = $arrRent['address'];
                        $householdHouseMsg->area = $arrRent['area'];
                        $householdHouseMsg->firsttime_check_in = $arrRent['firsttimeCheckIn'];
                        $householdHouseMsg->order = $arrRent['order'];
                        $householdHouseMsg->room_number = $arrRent['roomNumber'];
                        $householdHouseMsg->remark = $arrRent['remark'];
                        $householdHouseMsg->household_id = $resultId;
                        $householdHouseMsg->save();

                        \App\libraries\Util\calculateLastOneMonthRent($tmpHouseholdMsg, $householdHouseMsg);
                    }

                }

                Excel::create('出错反馈', function ($excel) use ($errorArr) {
                    $excel->sheet('出错反馈', function ($sheet) use ($errorArr) {
                        $sheet->rows($errorArr);
                    });
                })->export('xls');


            });

        }

    }
}
