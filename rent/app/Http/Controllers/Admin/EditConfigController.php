<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB;

class EditConfigController extends Controller
{

    /**
     *新增区域
     */
    public function addArea(Request $Request)
    {
        $name = $Request->input('name');
        DB::table('config')->insert([
            'name' => $name
        ]);
        return response()->json('success');
    }


}
