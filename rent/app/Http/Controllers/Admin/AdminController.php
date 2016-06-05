<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use DB;
use Validator;
use App\User;

class AdminController extends Controller
{

    /**
     * 进入新增账号页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addUserView(){
        return view('option.addUserView',[
            'active' => 'addUserView',
            'sildedown' => 'userView'
        ]);
    }

    /**
     * 进入管理账号页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageUserView(){
        $users = User::where('name','!=','admin')
            ->get();

        return view('option.manageUserView',[
            'active' => 'manageUserView',
            'users' => $users,
            'sildedown' => 'userView'
        ]);
    }

    /**
     * 新增账号
     * @param Request $request
     */
    public function addUser(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json('failed');
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);


    }

    /**
     * 重置密码
     * @param Request $request
     * @return mixed
     */
    public function resetPWD(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json('failed');
        }
        $user = User::where('id',$data['id'])
            ->first();
        $user->password = bcrypt($data['password']);
        $user->save();
        return response()->json('success');
    }

    /**
     * 冻结或解冻账号
     * @param $id
     * @param $flag
     * @return mixed
     */
    public function lock($id,$flag){
        $user = User::where('id',$id)
            ->first();
        $user->active = $flag;
        $user->save();
        return response()->json('success');
    }

}
