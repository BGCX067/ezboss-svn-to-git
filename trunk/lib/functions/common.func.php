<?php
/*
 * 通用函数库
 */

/**
 * 取得一个AdoDB的数据库连接对象
 */
function get_conn($dbhost = '', $dbname = '', $dbuser = '', $dbpsw = '', $charset = '') {
	require_once EZBOSS_CLASSES_PATH . DIRECTORY_SEPARATOR . 'DataBase.class.php';
	if ($dbhost == '') {
		global $dbhost, $dbname, $dbuser, $dbpsw, $charset;
	}
	$db = new DataBase ( $dbhost, $dbname, $dbuser, $dbpsw, $charset );
	try {
		$conn = $db->getConn ();
		return $conn;
	} catch ( Exception $e ) {
		echo "数据库连接失败";
		exit ();
	}

}

//获取在线IP
function getonlineip($format = 0) {
	global $_SGLOBAL;
	
	if (empty ( $_SGLOBAL ['onlineip'] )) {
		if (getenv ( 'HTTP_CLIENT_IP' ) && strcasecmp ( getenv ( 'HTTP_CLIENT_IP' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_CLIENT_IP' );
		} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' ) && strcasecmp ( getenv ( 'HTTP_X_FORWARDED_FOR' ), 'unknown' )) {
			$onlineip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} elseif (getenv ( 'REMOTE_ADDR' ) && strcasecmp ( getenv ( 'REMOTE_ADDR' ), 'unknown' )) {
			$onlineip = getenv ( 'REMOTE_ADDR' );
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown' )) {
			$onlineip = $_SERVER ['REMOTE_ADDR'];
		}
		preg_match ( "/[\d\.]{7,15}/", $onlineip, $onlineipmatches );
		$_SGLOBAL ['onlineip'] = $onlineipmatches [0] ? $onlineipmatches [0] : 'unknown';
	}
	if ($format) {
		$ips = explode ( '.', $_SGLOBAL ['onlineip'] );
		for($i = 0; $i < 3; $i ++) {
			$ips [$i] = intval ( $ips [$i] );
		}
		return sprintf ( '%03d%03d%03d', $ips [0], $ips [1], $ips [2] );
	} else {
		return $_SGLOBAL ['onlineip'];
	}
}

/*
 * 使用log4php写日志
 * @author: LiJunhui
 * @param $msg:string,日志内容
 * @param $level:string,日志级别 debug|info|warn|error|fatal
 * 
 * */
function writelog($msg, $level = 'debug') {
	
	$level = strtolower ( $level );
	$logger = get_logger ();
	$logger->$level ( $msg );
}

/*
 * 获取一个log4php对象
 * @author: LiJunhui
 * */
function get_logger() {
	require_once EZBOSS_THIRDPARTY_PATH . '/Apache/log4php/Logger.php';
	Logger::configure ( EZBOSS_WEBROOT . '/config/appender_dailyfile.properties' );
	
	$logger = Logger::getRootLogger ();
	
	return $logger;
} 

function lang($lang_key, $force = true) {
	return isset($GLOBALS['lang'][$lang_key]) ? $GLOBALS['lang'][$lang_key] : ($force ? $lang_key : '');
}