<?php

namespace App\Http\Controllers\Admin;

use App\HouseholdMsg;
use App\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;

class RentManageController extends Controller
{

    /**
     * 进入房租信息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function RentMsgListView()
    {
        $input = Input::all();
        if (sizeof($input) == 0) {
            $input = [
                'firsttimeCheckIn' => '',
                'beginPayTime' => '',
                'endPayTime' => ''
            ];
            $rents = Rent::orderBy('household_id', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(15);
        } else {
            $where = array();
            if ($input['firsttimeCheckIn'] != '') {
                $where['firsttime_check_in'] = $input['firsttimeCheckIn'];
            }
            $rents = Rent::where($where)
                ->orderBy('household_id', 'asc')
                ->orderBy('id', 'asc')
                ->paginate(15);
        }


        return view('option.RentMsgListView', [
            'active' => 'rentMsgView',
            'rents' => $rents,
            'input' => $input,
            'sildedown' => 'household'
        ]);
    }

    /**
     * 显示详情
     * @param $id
     * @return mixed
     */
    public function showDetail($id)
    {
        $rent = Rent::where('id', '=', $id)->first();
        return response()->json($rent);
    }

    /**
     * 显示房租信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewRent($id)
    {
        $input = Input::all();
        $householdMsg = HouseholdMsg::where('id', $id)->first();
        if (sizeof($input) == 0) {
            $input = [
                'firsttimeCheckIn' => '',
                'beginPayTime' => '',
                'endPayTime' => ''
            ];
            $rents = $householdMsg->rent()->orderBy('id', 'asc')->paginate(15);
        } else {
            $where = array();
            if ($input['firsttimeCheckIn'] != '') {
                $where['firsttime_check_in'] = $input['firsttimeCheckIn'];
            }
            date_default_timezone_set('PRC');
            $rents = $householdMsg->
            rent()
                ->where($where)
                ->whereBetween('time_pay_rent',
                    [
                        ($input['beginPayTime'] == '' ? '1970-01-01' : $input['beginPayTime']),
                        ($input['endPayTime'] == '' ? date('Y-m-d 23:59:59', time()) : $input['endPayTime'].' 23:59:59')
                    ])
                ->orderBy('id', 'asc')
                ->paginate(15);
        }


        return view('option.RentMsgListView', ['active' => 'rentMsgView',
            'rents' => $rents,
            'input' => $input,
            'id' => $id,
            'sildedown' => 'household'
        ]);
    }
}
