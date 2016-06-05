<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use App\HouseholdMsg;
use App\Rent;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{

    public function searchRentByOne(){
        $data = Input::All();
        //将二维数组转化成一维数组
        if (\App\libraries\Util\what_kind_of_array($data['data']) == 2) {
            $data = \App\libraries\Util\array_two_to_one($data['data']);
        }
        $householdMsg = HouseholdMsg::where('name',$data['name'])
            ->where('job_number',$data['jobNumber'])
            ->where('institution',$data['institution'])
            ->first();
        if($householdMsg == null){
            return response()->json('none');
        }
        $rent = Rent::where('household_id',$householdMsg->id)
            ->orderBy('id','asc')
            ->get();
        return response()->json($rent);
    }
}
