<?php
/*
 * @desc:Ä£¿é»ù´¡Àà
 * @author: LiJunhui
 * @Id: ModBase.class.php
 */
class BaseClass {
	protected $db;
	
	function __construct() {
		global $dbhost, $sys_dbname, $dbname, $dbuser, $dbpsw, $charset;
		$this->db = get_conn ( $dbhost, $dbname, $dbuser, $dbpsw, $charset );
		if ($this->db->ErrorNo () > 0) {
			echo $this->db->ErrorMsg () ;
			exit ();
		}
		if (EZBOSS_DB_DEBUG == true) {
			$this->db->debug = true;
		}
	}
	
	function __destruct() {
	
	}

}