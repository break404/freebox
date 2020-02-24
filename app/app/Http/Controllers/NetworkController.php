<?php

namespace App\Http\Controllers;

use DemeterChain\C;
use session;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Models\ConfigModel;

class NetworkController extends Controller
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
     * 显示系统设置视图
     */
    public function index(Request $request)
    {
        $config = ConfigModel::where('id', 1)->first();

        return view('network.index', ['config' => $config]);
    }


    /*
     * 外网设置
     */

    public function wanset(Request $request){
        if(!$request->has('act')){
            $config = ConfigModel::where('id',1)->first();
            return view('network.wanset',['config'=>$config]);
        }
    }


    /*
     * 联网设置-刷新dhcp地址
     */
    public function set_dhcp_reconnect(Request $request)
    {
        //先判断ETH0当前连接模式是否为DHCP模式
        $cmd = "sudo cat /etc/network/interfaces | grep 'iface eth0 inet' |awk '{print $4}'";
        exec($cmd, $rec, $status);
        if ($status != 0 || $rec[0] !== 'dhcp') {
            return response()->json(['code' => 0, 'msg' => '当前联网模式不是DHCP，不能使用此功能！']);
        }
        //重新获得一个DHCP地址
        $cmd = "sudo dhclient eth0";
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '刷新DHCP服务失败！']);
        }
        return response()->json(['code' => 200, 'msg' => "刷新DHCP服务成功！"]);
    }

    /*
     * 设置联网方式为DHCP
     *
     */
    public function set_dhcp(Request $request)
    {
        //先判断ETH0当前连接模式是否为DHCP模式
        $cmd = "sudo cat /etc/network/interfaces | grep 'iface eth0 inet' |awk '{print $4}'";
        exec($cmd, $rec, $status);
        if ($status == 0 && $rec[0] == 'dhcp') {
            return response()->json(['code' => 200, 'msg' => '当前联网模式为DHCP,不用重复设置！']);
        }
        //开始设置联网方式为DHCP模式
        $config = ConfigModel::where('id', 1)->first();
        $bak_path = env('CONF_PATH') . '/network/interfaces.bak';
        $cmd = "sudo cp /etc/network/interfaces " . $bak_path;
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '备份文件失败！']);
        }
        //重新写入一个新的interface
        $cmd = "sudo /bin/bash " . env('CONF_PATH') . '/script/set_dhcp_mode.sh ' . $config->lan_ip . " " . $config->lan_netmask;
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '设置联网模式为DHCP失败！']);
        }
        //保存到数据库中
        $config->wan_mode = 'dhcp';
        $config->save();
        return response()->json(['code' => 200, 'msg' => '设置联网模式为DHCP成功！']);
    }

    /*
     * 设置联网模式为static
     */

    public function set_static(Request $request)
    {
        $ip = $request->get('ip');
        $mask = $request->get('mask');
        $gateway = $request->get('gateway');
        $dns1 = $request->get('dns1');
        $dns2 = $request->get('dns2');

        //检查IP 地址是否合法
        if(!filter_var($ip, FILTER_VALIDATE_IP) || !filter_var($mask, FILTER_VALIDATE_IP) || !filter_var($gateway, FILTER_VALIDATE_IP)){
            return response() ->json(['code'=>0,'msg'=>'提交的IP地址不合法！']);
        }

        if(strlen($dns1) >5){
            if(!filter_var($dns1, FILTER_VALIDATE_IP)){
                return response() ->json(['code'=>0,'msg'=>'提交的DNS1地址不合法！']);
            }
        }else{
            $dns1 = '1.1.1.1';
        }
        if(strlen($dns2) >5){
            if(!filter_var($dns2, FILTER_VALIDATE_IP)){
                return response() ->json(['code'=>0,'msg'=>'提交的DNS2地址不合法！']);
            }
        }else{
            $dns2 = '8.8.8.8';
        }

        $config = ConfigModel::where('id',1) ->first();
        //重新写入一个新的interface
        $cmd = "sudo /bin/bash " . env('CONF_PATH') . '/script/set_static_mode.sh '. ' ' . $ip . ' ' . $mask . ' ' . $gateway . ' '  . $config->lan_ip . " " . $config->lan_netmask;
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '设置联网模式为STATIC失败！']);
        }

        //更新数据库
        $config->wan_mode='static';
        $config->wan_ip = $ip;
        $config->wan_netmask =$mask;
        $config->wan_gateway =$gateway;
        $config->wan_dns1 =$dns1;
        $config->wan_dns2 =$dns2;
        $config->save();
        return response()->json(['code' => 200, 'msg' => '设置联网模式为STATIC成功！']);
    }

    /*
     * 设置联网模式为pppoe
     */
    public function set_pppoe(Request $request){
        $username = $request->get('username');
        $pwd = $request->get('pwd');
        $dns1 = $request->get('dns1');
        $dns2 = $request->get('dns2');
        return response()->json(['code'=>0,'msg'=>"暂时不支持PPPOE拨号！"]);
    }


    /*
     * 内网设置
     */
    public function lanset(Request $request)
    {
        if(!$request->has('act')){
            $config = ConfigModel::where('id',1)->first();
            return view('network.lanset',['config'=>$config]);
        }
        $config = ConfigModel::where('id', 1)->first();
        $ip = $request->get('ip');
        $ip = trim($ip);
        $mask = $request->get('mask');
        $mask = trim($mask);
        if (!filter_var($ip, FILTER_VALIDATE_IP) || !filter_var($mask, FILTER_VALIDATE_IP)) {
            return response()->json(['code' => 0, 'msg' => '提交的IP地址不合法！']);
        }
        //判断是否跟数据库存储的一样
        if ($ip == $config->lan_ip && $mask == $config->lan_netmask) {
            return response()->json(['code' => 200, 'msg' => '未修改任何数据！']);

        } else {
            //先替换/etc/network/interfaces文件中的IP地址
            $rec = '';
            $cmd = "sudo sed -i 's/" . $config->lan_ip . "/" . $ip . "/g' /etc/network/interfaces";
            exec($cmd, $rec, $status);
            if ($status != 0) {
                return response()->json(['code' => 0, 'msg' => '修改IP地址失败！']);
            }

            //替换mask地址，有可能两个网卡的 mask一样，则替换第二一个
            $rec = '';
            if ($config->lan_netmask == $config->wan_netmask) {
                $cmd = "sudo sed -i ':a;N;$!ba;s/" . $config->lan_netmask . "/" . $mask . "/2' /etc/network/interfaces";
            } else {
                $cmd = "sudo sed -i 's/" . $config->lan_netmask . "/" . $mask . "/g' /etc/network/interfaces";
            }
            exec($cmd, $rec, $status);
            if ($status != 0) {
                return response()->json(['code' => 0, 'msg' => '修改MASK地址失败！']);
            }

            //因为nginx监听了LAN口的IP 地址，则还需要替换nginx配置文件中的IP地址
            $rec = '';
            $cmd = "sudo sed -i 's/" . $config->lan_ip . "/" . $ip . "/g' /usr/local/nginx/conf/nginx.conf";
            exec($cmd, $rec, $status);
            if ($status != 0) {
                return response()->json(['code' => 0, 'msg' => '修改Nginx监听IP地址失败！']);
            }

            //还需要修改/etc/dnsmasq.conf中的监听地址
            $rec = '';
            $cmd = "sudo sed -i 's/" . $config->lan_ip . "/" . $ip . "/g' /etc/dnsmasq.conf";
            exec($cmd, $rec, $status);
            if ($status != 0) {
                return response()->json(['code' => 0, 'msg' => '修改Dnsmasq监听IP地址失败！']);
            }

            //替换DHCP地址范围
            $cip = explode('.', $config->lan_ip);
            $sip = explode('.', $ip);
            $cip = $cip[0] . '.' . $cip[1] . '.' . $cip[2];
            $sip = $sip[0] . '.' . $sip[1] . '.' . $sip[2];
            $rec = '';
            $cmd = "sudo sed -i 's/" . $cip . "/" . $sip . "/g' /etc/dnsmasq.conf";
            exec($cmd, $rec, $status);
            if ($status != 0) {
                return response()->json(['code' => 0, 'msg' => '修改DHCP地址池失败！']);
            }

            //更新DHCP地址范围到数据库中
            $dhcp_start = explode('.', $config->dhcp_start);
            $dhcp_end = explode('.', $config->dhcp_end);
            $dhcp_start = $sip . '.' . $dhcp_start[3];
            $dhcp_end = $sip . '.' . $dhcp_end[3];

            //更新新IP和MASK到数据库中
            $config->lan_ip = $ip;
            $config->lan_netmask = $mask;
            $config->dhcp_start = $dhcp_start;
            $config->dhcp_end = $dhcp_end;
            $config->save();

            //重新启动服务
            return response()->json(['code' => 200, 'msg' => '数据修改成功,系统重启中...']);

        }
    }

    /*
     * WIFI设置
     */
    public function wifiset(Request $request)
    {
        if(!$request->has('act')){
            $config = ConfigModel::where('id',1)->first();
            return view('network.wifiset',['config'=>$config]);

        }
        $ssid = trim($request->get('ssid'));
        $tunnel = trim($request->get('tunnel'));
        $pwd = trim($request->get('pwd'));
        $ssid_enable = trim($request->get('ssid_enable'));
        //先判断TUNNEL是否为1-13之间的整数
        if (!is_numeric($tunnel) || strpos($tunnel, '.')) {
            return response()->json(['code' => 0, 'msg' => '信道不为整数！']);
        }

        if ($tunnel < 1 || $tunnel > 13) {
            return response()->json(['code' => 0, 'msg' => '信道应该为1-13之间！']);
        }

        //判断SSID 密码是否大于8位
        if (strlen($pwd) < 8) {
            return response()->json(['code' => 0, 'msg' => 'WIFI密码长度应该大于等于8位！']);
        }

        //判断SSID是否有效
        if (strlen($ssid) < 1) {
            return response()->json(['code' => 0, 'msg' => 'SSID无效！']);
        }
        //开始修改
        $config = ConfigModel::where('id', 1)->first();

        //重新写入一个新的interface
        $cmd = "sudo /bin/bash " . env('CONF_PATH') . '/script/set_wifi.sh '. ' ' . $ssid . ' ' . $tunnel . ' ' . $pwd;
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => 'WI-FI设置失败！']);
        }

        //修改结果保存到数据库
        $config->wifi_ssid = $ssid;
        $config->wifi_tunnel = $tunnel;
        $config->wifi_password = $pwd;
        $config->save();

        //重新启动服务
        return response()->json(['code' => 200, 'msg' => '数据修改成功,系统重启后生效...']);
    }

    /*
     * DHCP服务器设置
     */

    public function dhcpset(Request $request)
    {
        if(!$request->has('act')){
            $config= ConfigModel::where('id',1)->first();
            return view('network.dhcpset',['config'=>$config]);
        }

        $dhcp_start = $request->get('dhcp_start');
        $dhcp_end = $request->get('dhcp_end');
        $dhcp_time = $request->get('dhcp_time');
        $dhcp_enable = $request->get('dhcp_enable');
        $dhcp_start = trim($dhcp_start);
        $dhcp_end = trim($dhcp_end);
        $dhcp_time = trim($dhcp_time);

        if (!filter_var($dhcp_start, FILTER_VALIDATE_IP) || !filter_var($dhcp_end, FILTER_VALIDATE_IP)) {
            return response()->json(['code' => 0, 'msg' => '提交的IP地址不合法!']);
        }

        if (!is_numeric($dhcp_time)) {
            return response()->json(['code' => 0, 'msg' => '提交的DHCP租期不合法!']);
        }


        $config = ConfigModel::where('id', 1)->first();

        //判断提交的DHCP开始IP 是否等于数据库中的IP地址，且不能跟lan_ip一样
        if ($dhcp_start == $config->lan_ip || $dhcp_end == $config->lan_ip) {
            return response()->json(['code' => 0, 'msg' => 'DHCP地址池不能包含网关IP!']);
        }

        //判断提交的DHCP开始IP 的前3位是否和网关的前3位IP一样
        $dhcp_s3 = explode('.', $dhcp_start);
        $dhcp_s3 = $dhcp_s3[0] . '.' . $dhcp_s3[1] . '.' . $dhcp_s3[2];

        $dhcp_c3 = explode('.', $config->lan_ip);
        $dhcp_c3 = $dhcp_c3[0] . '.' . $dhcp_c3[1] . '.' . $dhcp_c3[2];
        if ($dhcp_c3 != $dhcp_s3) {
            return response()->json(['code' => 0, 'msg' => 'DHCP地址池的开始IP必须和网关在同一个网段!']);
        }

        //替换地址池
        $rec = '';
        $cmd = $cmd = "sudo sed -i 's/" . $config->dhcp_start . "/" . $dhcp_start . "/g' /etc/dnsmasq.conf";
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '修改DHCP开始地址池失败！']);
        }

        $rec = '';
        $cmd = $cmd = "sudo sed -i 's/" . $config->dhcp_end . "/" . $dhcp_end . "/g' /etc/dnsmasq.conf";
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '修改DHCP结束地址池失败！']);
        }

        //替换租约
        $szy = $dhcp_time . 'h';
        $czy = $config->dhcp_time . 'h';
        $rec = '';
        $cmd = $cmd = "sudo sed -i 's/" . $czy . "/" . $szy . "/g' /etc/dnsmasq.conf";
        exec($cmd, $rec, $status);
        if ($status != 0) {
            return response()->json(['code' => 0, 'msg' => '修改DHCP租约失败！']);
        }

        //更新数据库
        $config->dhcp_start = $dhcp_start;
        $config->dhcp_end = $dhcp_end;
        $config->dhcp_time = $dhcp_time;
        $config->save();

        //重新启动服务
        return response()->json(['code' => 200, 'msg' => '数据修改成功,系统重启中...']);
    }







}
