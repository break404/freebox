#!/bin/bash
#根据传入的WLAN0的静态IP和NETMASK重新生成一个interface

sudo rm -rf /etc/network/interfaces
sudo echo "# interfaces(5) file used by ifup(8) and ifdown(8)" >/etc/network/interfaces
sudo echo "" >> /etc/network/interfaces
sudo echo "# Please note that this file is written to be used with dhcpcd" >> /etc/network/interfaces
sudo echo "# For static IP, consult /etc/dhcpcd.conf and 'man dhcpcd.conf'" >> /etc/network/interfaces
sudo echo "" >> /etc/network/interfaces
sudo echo "# Include files from /etc/network/interfaces.d:" >> /etc/network/interfaces
sudo echo "source-directory /etc/network/interfaces.d" >> /etc/network/interfaces
sudo echo "" >>/etc/network/interfaces
sudo echo "auto lo" >> /etc/network/interfaces
sudo echo "iface lo inet loopback" >> /etc/network/interfaces
sudo echo "" >> /etc/network/interfaces
sudo echo "auto eth0" >> /etc/network/interfaces
sudo echo "iface eth0 inet static" >> /etc/network/interfaces
sudo echo "       address $1" >> /etc/network/interfaces
sudo echo "       netmask $2" >> /etc/network/interfaces
sudo echo "       gateway $3" >> /etc/network/interfaces
sudo echo "" >> /etc/network/interfaces
sudo echo "allow-hotplug wlan0" >> /etc/network/interfaces
sudo echo "iface wlan0 inet static" >> /etc/network/interfaces
sudo echo "        address $4" >> /etc/network/interfaces
sudo echo "        netmask $5" >> /etc/network/interfaces
sudo chown root:root /etc/network/interfaces
