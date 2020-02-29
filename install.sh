#!/bin/bash

#开启ssh服务
update-rc.d ssh defaults

#替换源
#echo "deb http://mirrors.aliyun.com/raspbian/raspbian/ stretch main contrib non-free rpi" > /etc/apt/sources.list
#echo "deb http://mirrors.ustc.edu.cn/archive.raspberrypi.org/debian/ stretch main ui" > /etc/apt/sources.list.d/raspi.list
apt-get update

#下载数据
cd /root
wget https://github.com/break404/freebox/archive/master.zip
unzip master.zip

wget http://nginx.org/download/nginx-1.16.1.tar.gz
tar -zxvf nginx-1.16.1.tar.gz

wget https://www.php.net/distributions/php-7.3.15.tar.gz
tar -zxvf php-7.3.15.tar.gz

wget https://www.openssl.org/source/openssl-1.1.1d.tar.gz
tar -zxvf openssl-1.1.1d.tar.gz

wget https://download.savannah.gnu.org/releases/freetype/freetype-2.9.tar.gz
tar -zxvf freetype-2.9.tar.gz

#安装依赖包
apt-get install libcurl3-openssl-dev -y
apt-get install libjpeg-dev -y
apt-get install libxslt-dev -y
apt-get install libxml2-dev -y
apt-get install libpcre3 libpcre3-dev -y
apt-get install daemon -y 
apt-get install curl -y

#安装PHP
cd openssl-1.1.1d
./config
make
make install
cd ..

cd freetype-2.9
./configure
make
make install
cd ..

cd php-7.3.15
./configure  --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --with-config-file-scan-dir=/usr/local/php/conf.d --enable-fpm --with-fpm-user=pi --with-fpm-group=pi --enable-mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-freetype-dir=/usr/local/freetype --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --enable-mbregex --enable-mbstring --enable-intl --enable-ftp --with-gd --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --without-libzip --enable-soap --with-gettext --disable-fileinfo --enable-opcache --with-xsl
make
make install
cd ..

#安装nginx
cd nginx-1.16.1
./configure --user=pi --group=pi --prefix=/usr/local/nginx --with-http_stub_status_module --with-http_ssl_module  --with-http_gzip_static_module --with-http_sub_module
make
make install
cd ..

#删除默认文件
rm -rf /usr/local/nginx/conf/*
rm -rf /usr/local/php/etc/*

#安装freebox
cd freebox-master
mkdir -p /var/log/nginx
mkdir -p /home/pi/freebox

chmod -R 777 /var/log/nginx

cd nginx-conf
cp -rf * /usr/local/nginx/conf/
cd ..

cd php-etc
cp -rf * /usr/local/php/etc/
cd ..

cd init.d
cp nginx /etc/init.d/
cp php-fpm /etc/init.d/
cd ..

chmod +x /etc/init.d/nginx
chmod +x /etc/init.d/php-fpm
update-rc.d nginx defaults
update-rc.d php-fpm defaults

cp -rf app /home/pi/freebox/
cp -rf conf /home/pi/freebox/
cp -rf db /home/pi/freebox/

chown -R pi:pi /home/pi

#安装v2ray
bash <(curl -L -s https://install.direct/go.sh)

#应用nat规则
cp ./conf/iptables.ipv4.nat /etc/iptables.ipv4.nat
iptables-restore </etc/iptables.ipv4.nat 

#安装dnsmasq hostapd
apt-get  install dnsmasq hostapd -y
cp ./conf/sysctl.conf /etc/
cp ./conf/dhcpcd.conf /etc/
cp -rf ./conf/hostapd /etc/
sysctl -p

#初始化网络
cp ./conf/network/interfaces /etc/network/

cp ./conf/rc.local /etc/
chmod +x /etc/rc.local









