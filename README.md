# freebox
一个能把你树莓派盒子变成可以科学上网的gui界面的路由器。

# 安装步骤（以3b+为例）

1 下载树莓派lite版本的镜像,下载地址：https://www.raspberrypi.org/downloads/raspbian/
2 将下载后的文件解压缩，使用win32diskmanager工具将img文件写入到tf卡中，win32diskmanager软件下载地址：https://sourceforge.net/projects/win32diskimager/
3 树莓派默认系统是不开启ssh服务的，为了下一步的操作，请将tf卡拔出电脑，在插入进去，应该可以看到一个boot分区，在此分区下创建一个ssh的空文件，不要任何后缀
4 将tf卡插入到树莓派中，接入开启了dhcp服务的路由器下，加电启动。
5 启动后就可以在电脑中ssh登陆后进行下一步的安装。

登陆树莓派：
默认root账号是禁用的，只能用账号：pi 密码：raspberry登陆
登陆后请执行sudo passwd root 为root账号添加一个密码，并执行sudo passwd --unlock root 启用root账户

# 注意

树莓派默认安装后是没有完全识别tf卡的容量的，所以需要先设置识别全部tf卡的容量和开启ssh访问
教程：https://blog.csdn.net/weixin_40973138/article/details/84027436

# 正式安装

  执行目录下的install.sh，将会启动自动安装，因为要编译php nginx 因此，这个过程可能会很漫长，建议你去喝杯咖啡在睡一觉可能就好了。

 



![avatar](https://github.com/break404/freebox/blob/master/screen1.jpg)
