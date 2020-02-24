<?php

namespace App\Http\Controllers;

use http\Env\Response;
use session;
use Illuminate\Http\Request;
use App\Models\V2rayModel;
use Illuminate\Support\Facades\Log;


class V2rayController extends Controller
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

    public function index(Request $request){
        $v2ray = V2rayModel::get();
        return view('proxy.v2ray');
    }

    public function create(Request $request){
        return view('proxy.v2ray_create');
    }

    public function edit(Request $request){
        $id = $request->get('id');
        $v2ray = V2rayModel::where('id',$id) ->first();
        return view('proxy.v2ray_edit',['v2ray'=>$v2ray]);
    }

    public function data(Request $request){
        $query = new V2rayModel();
        $res = $query->paginate((int)$request->get('limit', 10))->toArray();
        $data = [
            'code' => 0,
            'msg' => '正在请求中...',
            'count' => $res['total'],
            'data' => $res['data']
        ];
        return response()->json($data);
    }

    public function store(Request $request){
        $country = trim($request->get('country'));
        $country = strtoupper($country);
        $vnext_address = trim($request->get('vnext_address'));
        $vnext_ip = trim($request->get('vnext_ip'));
        $vnext_port = trim($request->get('vnext_port'));
        $user_id = trim($request->get('user_id'));
        $user_aid = trim($request->get('user_aid'));
        $stream_network = trim($request->get('stream_network'));
        $stream_network_security = trim($request->get('stream_network_security'));
        $ws_path = trim($request->get('ws_path'));
        $ws_header_host = trim($request->get('ws_header_host'));

        if(!is_numeric($vnext_port)){
            return response() ->json(['code'=>0,'msg'=>'校验端口数据格式失败！']);
        }

        if(strlen($country) !=2){
            return response() ->json(['code'=>0,'msg'=>'校验地区数据格式失败！']);
        }

        if (!filter_var($vnext_ip, FILTER_VALIDATE_IP) ) {
            return response()->json(['code' => 0, 'msg' => '校验IP地址格式失败！']);
        }

        $v2ray = new V2rayModel();
        $v2ray->country =$country;
        $v2ray ->vnext_address =$vnext_address;
        $v2ray ->vnext_ip =$vnext_ip;
        $v2ray->vnext_port=$vnext_port;
        $v2ray->user_id=$user_id;
        $v2ray->user_aid=$user_aid;
        $v2ray->stream_network=$stream_network;
        $v2ray->stream_network_security =$stream_network_security;
        $v2ray->ws_path=$ws_path;
        $v2ray->ws_header_host=$ws_header_host;
        $v2ray->save();

        return response() ->json(['code'=>200,'msg'=>'增加V2ray节点成功！']);
    }

    public function delete(Request $request){
        $data = $request->get('field');
        $data = json_decode(base64_decode($data));
        foreach ($data as $d){
            $v2ray = V2rayModel::where('id',$d->id)->first();
            $v2ray->delete();
        }

        return response() ->json(['code'=>200,'msg'=>'删除V2ray节点成功！']);

    }

    public function update(Request $request){
        $id = $request->get('id');
        $country = trim($request->get('country'));
        $country = strtoupper($country);
        $vnext_address = trim($request->get('vnext_address'));
        $vnext_ip = trim($request->get('vnext_ip'));
        $vnext_port = trim($request->get('vnext_port'));
        $user_id = trim($request->get('user_id'));
        $user_aid = trim($request->get('user_aid'));
        $stream_network = trim($request->get('stream_network'));
        $stream_network_security = trim($request->get('stream_network_security'));
        $ws_path = trim($request->get('ws_path'));
        $ws_header_host = trim($request->get('ws_header_host'));

        if(!is_numeric($vnext_port)){
            return response() ->json(['code'=>0,'msg'=>'校验端口数据格式失败！']);
        }

        if(strlen($country) !=2){
            return response() ->json(['code'=>0,'msg'=>'校验地区数据格式失败！']);
        }

        if (!filter_var($vnext_ip, FILTER_VALIDATE_IP) ) {
            return response()->json(['code' => 0, 'msg' => '校验IP地址格式失败！']);
        }

        $v2ray = V2rayModel::where('id',$id)->first();
        $v2ray->country =$country;
        $v2ray ->vnext_address =$vnext_address;
        $v2ray ->vnext_ip =$vnext_ip;
        $v2ray->vnext_port=$vnext_port;
        $v2ray->user_id=$user_id;
        $v2ray->user_aid=$user_aid;
        $v2ray->stream_network=$stream_network;
        $v2ray->stream_network_security =$stream_network_security;
        $v2ray->ws_path=$ws_path;
        $v2ray->ws_header_host=$ws_header_host;
        $v2ray->save();
        return response() ->json(['code'=>200,'msg'=>'编辑V2ray节点成功！']);

    }

    public function app(Request $request){
        $id = $request->get('id');
        $v2ray =V2rayModel::where('id',$id)->first();
        /*if($v2ray->status==1){
            return response() ->json(['code'=>200,'msg'=>'当前节点在使用中，无需重复设置！']);
        }*/

        $rec = '';
        $cmd = "sudo /bin/bash " . env('CONF_PATH') . "/script/app_v2ray.sh ". $v2ray->vnext_address . " " . $v2ray->vnext_port.' '.$v2ray->user_id.' '.$v2ray->user_aid.' '.$v2ray->ws_path.' '.$v2ray->ws_header_host. " " . $v2ray->vnext_ip;
        //return $cmd;
        exec($cmd, $rec, $status);
        //return $cmd;
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '应用当前节点失败！']);
        }

        //设置数据库中其他节点状态
        V2rayModel::where('id','!=',$id)->update(['status' => 0]);


        $v2ray->status=1;
        $v2ray->save();

        $rec = '';
        $cmd = "sudo service v2ray restart";
        exec($cmd, $rec, $status);

        return response() ->json(['code'=>200,'msg'=>'应用当前节点成功！']);


    }



}
