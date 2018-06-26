#! /bin/bash

if [ "$(whoami)" != "root" ]
then
    sudo su -s "$0"
    exit
fi