<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use session;

class SystemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
     * 重启路由器
     */
    public function reboot(Request $request)
    {
        if(!$request->has('act')){
            return view('system.reboot');
        }

        if($request->get('act') == 'reboot'){
            $cmd = "sudo reboot";
            exec($cmd, $status, $rec);
            if ($status == 0) {
                return response()->json(['code' => 200, 'msg' => "命令执行成功!"]);
            } else {
                return response()->json(['code' => 0, 'msg' => "命令执行失败!"]);
            }
        }

        if($request->get('act') == 'shutdown'){
            $cmd = "sudo shutdown -h now";
            exec($cmd, $status, $rec);
            if ($status == 0) {
                return response()->json(['code' => 200, 'msg' => "命令执行成功!"]);
            } else {
                return response()->json(['code' => 0, 'msg' => "命令执行失败!"]);
            }
        }
    }

    /*
     * 升级路由器
     */
    public function upgrade(Request $request){
        if(!$request->has('act')){
            return view('system.upgrade');
        }
    }


}
