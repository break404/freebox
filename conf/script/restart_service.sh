#!/bin/bash
/etc/init.d/networking restart
/sbin/ifconfig eth0:1 10.251.251.251 netmask 255.255.255.0 broadcast 10.251.251.255 up
/etc/init.d/nginx restart

ps -aux|grep hostapd|grep -v grep|awk '{print $2}'| xargs kill -9

/usr/sbin/hostapd -B /etc/hostapd/hostapd.conf


