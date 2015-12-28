<?php
 
if(!defined('IN_EZBOSS')) {
	exit('Access Denied');
}

define('SOFT_NAME', 'EZBOSS');
define('SOFT_VERSION', '1.0.1');
define('SOFT_RELEASE', '20100701'); 

define ( EZBOSS_WEBROOT, dirname ( dirname ( __FILE__ ) ) );
define ( EZBOSS_LIB_PATH, EZBOSS_WEBROOT . DIRECTORY_SEPARATOR . 'lib' ); //lib����Ŀ¼
define ( EZBOSS_CONFIG_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'config' ); //�����ļ�Ŀ¼
define ( EZBOSS_FUNCTIONS_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'functions' ); //�����ļ�Ŀ¼
define ( EZBOSS_CLASSES_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'classes' ); //���ļ�Ŀ¼
define ( EZBOSS_THIRDPARTY_PATH, EZBOSS_LIB_PATH . DIRECTORY_SEPARATOR . 'thirdparty' ); //���������Ŀ¼
define ( EZBOSS_LANGUAGES_PATH, EZBOSS_WEBROOT . DIRECTORY_SEPARATOR . 'languages' ); //���������Ŀ¼
 
$sqlfile = EZBOSS_WEBROOT.'./db/ezboss.sql';  
 
$account_status = array
(
	'normal' => '1', //����
	'freezed' => '2' //����
); 

$product_unit = array
(
	'one' => '1', //����
	'day' => '2', //����
	'week' => '3', //����
	'month' => '4', //����
	'year' => '5' //����
); 

$product_status = array
(
	'available' => '1', //��Ч
	'unavailable' => '0' //��Ч
); 