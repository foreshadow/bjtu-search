@echo off

start python segserv.py
cd webapp
php -S 127.0.0.1:9999
