<?php
 
if(!defined('IN_EZBOSS')) {
	exit('Access Denied');
}

define('SOFT_NAME', 'EZBOSS');
define('SOFT_VERSION', '1.0.1');
define('SOFT_RELEASE', '20100701'); 

define ( EZBOSS_WEBROOT, dirname ( dirname ( __FILE__ ) ) );
define ( EZBOSS_LIB_PATH, EZBOSS_WEBROOT . DIRECTORY_SEPARATOR . 'lib' ); //lib所在目录
define ( EZBOSS_CONFIG_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'config' ); //配置文件目录
define ( EZBOSS_FUNCTIONS_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'functions' ); //函数文件目录
define ( EZBOSS_CLASSES_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'classes' ); //类文件目录
define ( EZBOSS_THIRDPARTY_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'thirdparty' ); //第三方类库目录
define ( EZBOSS_LANGUAGES_PATH, EZBOSS_WEBROOT . DIRECTORY_SEPARATOR . 'languages' ); //第三方类库目录
 
$sqlfile = EZBOSS_WEBROOT.'./db/ezboss.sql';  
 
$account_status = array
(
	'normal' => '1', //正常
	'freezed' => '2' //冻结
); 

$product_unit = array
(
	'one' => '1', //按次
	'day' => '2', //按天
	'week' => '3', //按周
	'month' => '4', //按月
	'year' => '5' //按年
); 

$product_status = array
(
	'available' => '1', //有效
	'unavailable' => '0' //无效
); 