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

        $all = array(['工号', '姓名', '单位', '入住时间', '上次结算时间', '本次结算时间', '房租']);
        $school = array(['工号', '姓名', '单位', '入住时间', '上次结算时间', '本次结算时间', '房租']);
        $province = array(['工号', '姓名', '单位', '入住时间', '上次结算时间', '本次结算时间', '房租']);
        $lease = array(['工号', '姓名', '单位', '入住时间', '上次结算时间', '本次结算时间', '房租']);
        $postdoctor = array(['工号', '姓名', '单位', '入住时间', '上次结算时间', '本次结算时间', '房租']);


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
                foreach ($rents as $rent) {
                    $tmp[0] = $householdMsg->job_number;
                    $tmp[1] = $householdMsg->name;
                    $tmp[2] = $householdMsg->institution;
                    $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
                    $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
                    $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
                    $tmp[6] = $rent->rent;
                    array_push($all, $tmp);
                }
            }
        }

        if (isset($input['school'])) {
            $rentArr['schoolInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 0)->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                foreach ($rents as $rent) {
                    $tmp[0] = $householdMsg->job_number;
                    $tmp[1] = $householdMsg->name;
                    $tmp[2] = $householdMsg->institution;
                    $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
                    $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
                    $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
                    $tmp[6] = $rent->rent;
                    array_push($school, $tmp);
                }
            }
        }

        if (isset($input['province'])) {
            $rentArr['provinceInput'] = 1;
            $householdMsgs = HouseholdMsg::where('type', 1)->get();
            foreach ($householdMsgs as $householdMsg) {
                $rents = Rent::where('household_id', $householdMsg->id)
                    ->whereBetween('time_pay_rent', [$BeginDate, date('Y-m-d 23:59:59', $endTime)])
                    ->get();
                foreach ($rents as $rent) {
                    $tmp[0] = $householdMsg->job_number;
                    $tmp[1] = $householdMsg->name;
                    $tmp[2] = $householdMsg->institution;
                    $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
                    $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
                    $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
                    $tmp[6] = $rent->rent;
                    array_push($province, $tmp);
                }
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
                foreach ($rents as $rent) {
                    $tmp[0] = $householdMsg->job_number;
                    $tmp[1] = $householdMsg->name;
                    $tmp[2] = $householdMsg->institution;
                    $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
                    $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
                    $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
                    $tmp[6] = $rent->rent;
                    array_push($lease, $tmp);
                }
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
                foreach ($rents as $rent) {
                    $tmp[0] = $householdMsg->job_number;
                    $tmp[1] = $householdMsg->name;
                    $tmp[2] = $householdMsg->institution;
                    $tmp[3] = date('Y-m-d', strtotime($rent->firsttime_check_in));
                    $tmp[4] = date('Y-m-d', strtotime($rent->lasttime_pay_rent)) == '1970-01-01' ? '无' : date('Y-m-d', strtotime($item->lasttime_pay_rent));
                    $tmp[5] = date('Y-m-d', strtotime($rent->time_pay_rent));
                    $tmp[6] = $rent->rent;
                    array_push($postdoctor, $tmp);
                }
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
                $excel->sheet('省发', function ($sheet) use ($province) {
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
            Excel::load($filePath, function ($reader) {
                //获取excel的第1张表
                $reader = $reader->getSheet(0);
                //获取表中的数据
                $results = $reader->toArray();

                //基本信息验证规则
                $baseRule = array(
                    'name' => 'required|between:1,10',
                    'jobNumber' => 'required|size:12|unique:household_msg,job_number',
                    'cardNumber' => 'required|size:19',
                    'institution' => 'required|between:1,20',
                    'hasHouse' => 'required|numeric|between:0,2',
                    'hasHouseTime' => 'required|date',
                    'type' => 'required|numeric|between:0,3'
                );

                //租房信息验证规则
                $rentRule = array(
                    'area' => 'required|numeric',
                    'firsttimeCheckIn' => 'required|date',
                    'order' => 'required|numeric|between:1,2'
                );

                $tmpHouseholdMsg = null;//临时的HouseholdMsg，用于第二间租房的HouseholdMsg信息存储
                $tmpOrder = 0;//用于判断同一用户两个租房的order是否重复，而order是否是规定的1和2则由valid来验证
                $flag = false;//基本信息存储出错，则第二间租房也不能存储，用此来做标志
                $count = 0;//用于标记租房数是否超过2间
                $errorArr = array(['姓名', '工号', '银行卡号', '单位', '是否有房', '无房时间', '有房时间', '是否离职', '离职时间', '发放方式', '区域', '房址', '面积', '入住时间', '第几间房']);

                for ($i = 1; $i < sizeof($results); $i++) {


                    $householdMsg = new HouseholdMsg();
                    $msg = $results[$i];

                    if ($msg[0] == null || $msg[1] == null || $msg[2] == null || $msg[3] == null) {

                        //第一条出错：所有信息回收
                        if ($flag) {
                            //记录错误信息
                            array_push($errorArr, $msg);
                            $flag = false;
                            continue;
                        }

                        //第一条正确，则接下去错误哪一条就回收哪一条
                        if ($tmpOrder == $msg[14]) {//租房号重复
                            //记录错误信息
                            $msg[0] = $tmpHouseholdMsg->name;
                            $msg[1] = $tmpHouseholdMsg->job_number;
                            $msg[2] = $tmpHouseholdMsg->card_number;
                            $msg[3] = $tmpHouseholdMsg->institution;
                            array_push($errorArr, $msg);
                            $flag = false;
                            continue;
                        }

                        $count++;
                        //第一条正确，第二条正确，则接下来的多出来的都回收
                        if ($count > 2) {//租房数多于二
                            //记录错误信息
                            $msg[0] = $tmpHouseholdMsg->name;
                            $msg[1] = $tmpHouseholdMsg->job_number;
                            $msg[2] = $tmpHouseholdMsg->card_number;
                            $msg[3] = $tmpHouseholdMsg->institution;
                            array_push($errorArr, $msg);
                            $flag = false;
                            continue;
                        }

                        $arrRent['area'] = $msg[12];
                        $arrRent['firsttimeCheckIn'] = $msg[13];
                        $arrRent['order'] = $msg[14];

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
                        $householdHouseMsg->household_id = $tmpHouseholdMsg->id;
                        $householdHouseMsg->save();

                        \App\libraries\Util\calculateLastOneMonthRent($tmpHouseholdMsg, $householdHouseMsg);

                    } else {

                        $count = 1;

                        $arrBase['name'] = $msg[0];
                        $arrBase['jobNumber'] = $msg[1];
                        $arrBase['cardNumber'] = $msg[2];
                        $arrBase['institution'] = $msg[3];
                        $arrBase['hasHouse'] = $msg[4];
                        if ($msg[4] == 0) {
                            $arrBase['hasHouseTime'] = $msg[5];
                        } else {
                            $arrBase['hasHouseTime'] = $msg[6];
                        }
                        $arrBase['type'] = $msg[9];

                        if (!Validator::make($arrBase, $baseRule)->fails()) {
                            $householdMsg->name = $arrBase['name'];
                            $householdMsg->job_number = $arrBase['jobNumber'];
                            $householdMsg->card_number = $arrBase['cardNumber'];
                            $householdMsg->institution = $arrBase['institution'];
                            $householdMsg->has_house = $arrBase['hasHouse'];
                            $householdMsg->type = $arrBase['type'];
                            //判断是存入有房时间还是无房时间
                            if ($arrBase['hasHouse'] == 0) {
                                $householdMsg->has_not_house_time = $arrBase['hasHouseTime'];
                            } else {
                                $householdMsg->has_house_time = $arrBase['hasHouseTime'];
                            }
                        } else {
                            //记录错误信息
                            array_push($errorArr, $msg);
                            $flag = true;
                            continue;
                        }

                        if ($msg[7] == 1) {
                            $householdMsg->is_dimission = 1;
                            if (!Validator::make(
                                ['dimissionTime' => $msg[8]],
                                ['dimissionTime' => 'required|date'])->fails()
                            ) {
                                $householdMsg->dimission_time = $msg[8];
                            } else {
                                //记录错误信息
                                array_push($errorArr, $msg);
                                $flag = true;
                                continue;
                            }
                        } else {
                            $householdMsg->is_dimission = 0;
                        }

                        $arrRent['area'] = $msg[12];
                        $arrRent['firsttimeCheckIn'] = $msg[13];
                        $tmpOrder = $arrRent['order'] = $msg[14];

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
