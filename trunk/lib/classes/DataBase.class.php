<?php
/**
 * DataBase 数据库操作处理类，封装AdoDB
 *
 */
require_once (EZBOSS_THIRDPARTY_PATH . '/adodb/adodb.inc.php'); # load code common to ADOdb 
class DataBase {
	private $conn;
	public function __construct($dbhost, $dbname, $dbuser, $dbpsw, $charset) {
		
		$conn = &ADONewConnection ( 'mysqlt' ); # create a connection  note:mysql不支持事务mysqlt支持事务
		$conn->Connect ( $dbhost, $dbuser, $dbpsw, $dbname ); # connect to MySQL, agora db 
		$conn->Execute ( "SET NAMES $charset" );
		//$conn->Debug = true;
		$this->conn = $conn;
	}
	
	public function getConn() {
		return $this->conn;
	}

}