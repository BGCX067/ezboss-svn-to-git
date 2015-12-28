<?php

class IPFilter extends BaseClass {
	
	var $ip;
	public function __construct($ip) {
		parent::__construct ();
		$this->ip = $ip;
	}
	
	function isAllowable() {
		if (in_array($this->ip, $GLOBALS['ip_list'])){
			return 1;
		} else {
			return 0;
		} 
	}
	/**
	 * 
	 */
	function __destruct() {
	
	}

}
?>