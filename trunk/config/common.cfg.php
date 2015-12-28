<?php
/* 
 * @ 配置文件  
 */

define ( EZBOSS_DEBUG, false ); //程序调试信息开关
define ( EZBOSS_DB_DEBUG, false ); //数据库调试信息开关 true false

//数据库连接配置

$dbhost = 'localhost';//数据库地址
$dbname = 'ezboss';//数据库名称

$dbuser = 'root';//数据库用户名
$dbpsw = 'password';//数据库密码

$charset = 'utf8';//数据库连接编码，建议不要修改

//允许访问的IP列表
$ip_list= array
(
	'127.0.0.1'
); 