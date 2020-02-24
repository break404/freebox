#!/bin/bash
sudo cp -rf /etc/v2ray/config.json /etc/v2ray/config.json.bak
sudo cp -rf /etc/v2ray/tpl.json /etc/v2ray/config.json
sudo sed -i "s/\"address\": \"88888\"/\"address\": \"$1\"/" /etc/v2ray/config.json
sudo sed -i "s/\"port\": 88888/\"port\": $2/" /etc/v2ray/config.json
sudo sed -i "s/\"id\": \"88888\"/\"id\": \"$3\"/" /etc/v2ray/config.json
sudo sed -i "s/\"alterId\": 88888/\"alterId\": $4/" /etc/v2ray/config.json
sudo sed -i "s#\"path\": \"88888\"#\"path\": \"$5\"#" /etc/v2ray/config.json
sudo sed -i "s/\"Host\": \"88888\"/\"Host\": \"$6\"/" /etc/v2ray/config.json
