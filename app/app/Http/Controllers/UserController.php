<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use session;

class UserController extends Controller
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

    public function login(Request $request)
    {
        //直接输出视图
        if (!$request->has('check')) {
            return view('user.login');
        }

        //检查密码
        $pwd = $request->get('pwd');
        //$pwd = password_hash($pwd,PASSWORD_BCRYPT,['cost' => 10]);
        //return $pwd;

        //从数据库中取出密码
        $res = UsersModel::where('name', 'admin')->first();
        //验证密码
        if (password_verify($pwd, $res->password)) {

            //保存登陆状态到session中
            app('session')->put('auth.status', 'on');

            return response()->json([
                'code' => 200,
                'msg' => '登陆成功'
            ]);
        } else {
            return response()->json([
                'code' => 0,
                'msg' => '登陆失败'
            ]);
        }
    }

    /*
     * 修改密码
     */
    public function changepwd(Request $request)
    {
        if(!$request->has('act')){
            return view('user.changepwd');
        }

        $oldPwd = $request->get('oldPwd');
        $newPwd = $request->get('newPwd');
        $user = UsersModel::where('name', 'admin')->first();

        //先验证原来密码是否正确
        if (password_verify($oldPwd, $user->password)) {
            //验证成功
            $user->password = password_hash($newPwd, PASSWORD_BCRYPT, ['cost' => 10]);
            $user->save();
            return response()->json(['code' => 200, 'msg' => "密码修改成功!"]);
        } else {
            return response()->json(['code' => 0, 'msg' => "原密码验证失败!"]);
        }
    }

    public function logout(Request $request){
        app('session')->put('auth.status', 'off');
        return redirect('/login');
    }

    //
}
