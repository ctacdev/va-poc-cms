#$/bin/bash


echo 'DROP DATABASE vad8; CREATE DATABASE vad8' | msql ; cat "$1" | mysql -uroot -pblahblah vad8 ; echo ; echo done ; echo

chromium-browser 'http://drupal8.dfrey-mbp.net:83/user/login'
