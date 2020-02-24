<?php

namespace App\Http\Controllers;

use session;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Models\ConfigModel;

class InitController extends Controller
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

    public function init(){
        //获取wlan0 MAC
        $rec = '';
        $cmd = "sudo ifconfig wlan0 | grep ether | awk '{print $2}'";
        exec($cmd,$rec,$status);
        $wlan0_mac = $rec[0];

        //获取wlan0 IP
        $rec = '';
        $cmd = "sudo ifconfig wlan0 | grep inet | awk '{print $2}'";
        exec($cmd,$rec,$status);
        $wlan0_ip = $rec[0];

        //获取wlan0 netmask
        $rec = '';
        $cmd = "sudo ifconfig wlan0 | grep inet | awk '{print $4}'";
        exec($cmd,$rec,$status);
        $wlan0_netmask = $rec[0];

        //获取eth0 MAC
        $rec = '';
        $cmd = "sudo ifconfig eth0 | grep ether | awk '{print $2}'";
        exec($cmd,$rec,$status);
        $eth0_mac = $rec[0];

        //获取eth0 IP
        $rec = '';
        $cmd = "sudo ifconfig eth0 | grep inet | awk '{print $2}'";
        exec($cmd,$rec,$status);
        $eth0_ip = $rec[0];

        //获取eth0 netmask
        $rec = '';
        $cmd = "sudo ifconfig eth0 | grep inet | awk '{print $4}'";
        exec($cmd,$rec,$status);
        $eth0_netmask = $rec[0];

        $config = ConfigModel::where('id',1)->first();
        $config ->wlan0_ip = $wlan0_ip;
        $config->wlan0_mac = $wlan0_mac;
        $config->wlan0_netmask=$wlan0_netmask;
        $config->eth0_ip =$eth0_ip;
        $config->eth0_mac =$eth0_mac;
        $config->eth0_netmask =$eth0_netmask;
        $config->save();
        return "inited ok";


    }



}
