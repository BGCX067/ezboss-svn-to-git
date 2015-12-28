<?php
/*
 * @desc:账户充值历史类
 * @$Id: RechargeHistory.class.php
 */
class RechargeHistory extends BaseClass {
	
	var $accountId; //账户编号
	/**
	 * 
	 */
	public function __construct($accountId) {
		parent::__construct ();
		$this->accountId = $accountId;
	}
	
	/**
	 * 得到账户充值历史记录
	 * @param $perPage
	 * @param $currPage
	 */
	function getHistoryList($perPage, $currPage) {
		$sqlstr = "select rh.ACCOUNT_ID, rh.AMOUNT, rh.TIME, rh.RECHARGE_WAY_ID, crw.NAME ";
		$sqlstr .= "from recharge_history rh, cfg_recharge_way crw ";
		$sqlstr .= "where rh.RECHARGE_WAY_ID = crw.ID and rh.ACCOUNT_ID='$this->accountId' "; 
		$sqlstr .= "order by TIME desc";
		$rslist = $this->db->PageExecute ( $sqlstr, $perPage, $currPage );
		$listarr = array ();
		$i = 0;
		foreach ( $rslist as $row ) {
			$listarr [$i] ['accountId'] = $row ['ACCOUNT_ID'];
			$listarr [$i] ['amount'] = $row ['AMOUNT'];
			$listarr [$i] ['time'] = $row ['TIME'];
			$listarr [$i] ['rechargeWayId'] = $row ['RECHARGE_WAY_ID'];
			$listarr [$i] ['rechargeWayName'] = $row ['NAME'];
			$i ++;
		}
		//print_r ( $listarr ); 
		return $listarr;
	}
	/**
	 * 
	 */
	function __destruct() {
	
	}

}
?>