#!/bin/bash

# Update nginx to match worker_processes to no. of cpu's
procs=$(cat /proc/cpuinfo | grep processor | wc -l)
sed -i -e "s/worker_processes  1/worker_processes $procs/" /etc/nginx/nginx.conf
composer install
yarn install
yarn encore dev
mkdir /var/www/var/log/supervisor
chmod -R 777 /var/www/var
# Start supervisord and services
/usr/bin/supervisord -n -c /etc/supervisord.conf