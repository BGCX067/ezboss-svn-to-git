<?php

class TransactionHistory extends BaseClass {
	
	var $accountId; //账户编号
	/**
	 * 
	 */
	public function __construct($accountId) {
		parent::__construct ();
		$this->accountId = $accountId;
	}
	
	/**
	 * 得到账户消费历史记录
	 * @param $perPage
	 * @param $currPage
	 */
	function getHistoryList($perPage, $currPage) {
		$sqlstr = "select th.ACCOUNT_ID, th.PRODUCT_CODE, th.PRODUCT_NAME, th.ORDER_COUNT, ";
		$sqlstr .= "th.ORDER_PRICE, th.ORDER_PRICE_UNIT, th.ORDER_AMOUNT, ";
		$sqlstr .= "th.TIME, th.PLATFORM_ID, cp.NAME ";
		$sqlstr .= "from transaction_history th, cfg_platform cp ";
		$sqlstr .= "where th.PLATFORM_ID = cp.ID ";
		$sqlstr .= "and th.ACCOUNT_ID='$this->accountId' ";
		$sqlstr .= "order by TIME desc"; 
		$rslist = $this->db->PageExecute ( $sqlstr, $perPage, $currPage );
		$listarr = array ();
		$i = 0;
		foreach ( $rslist as $row ) {
			$listarr [$i] ['accountId'] = $row ['ACCOUNT_ID'];
			$listarr [$i] ['productCode'] = $row ['PRODUCT_CODE'];
			$listarr [$i] ['productName'] = $row ['PRODUCT_NAME'];
			$listarr [$i] ['orderCount'] = $row ['ORDER_COUNT'];
			$listarr [$i] ['orderPrice'] = $row ['ORDER_PRICE'];  
			switch($row ['ORDER_PRICE_UNIT']){
				case $GLOBALS['product_unit']['one']: $listarr [$i] ['orderPriceUnitDesc'] = lang('product_unit_one'); break; 
				case $GLOBALS['product_unit']['day']: $listarr [$i] ['orderPriceUnitDesc'] = lang('product_unit_day'); break; 
				case $GLOBALS['product_unit']['week']: $listarr [$i] ['orderPriceUnitDesc'] = lang('product_unit_week'); break; 
				case $GLOBALS['product_unit']['month']: $listarr [$i] ['orderPriceUnitDesc'] = lang('product_unit_month'); break; 
				case $GLOBALS['product_unit']['year']: $listarr [$i] ['orderPriceUnitDesc'] = lang('product_unit_year'); break; 
			}    
			$listarr [$i] ['orderAmount'] = $row ['ORDER_AMOUNT'];
			$listarr [$i] ['time'] = $row ['TIME'];
			$listarr [$i] ['platformId'] = $row ['PLATFORM_ID'];
			$listarr [$i] ['platformName'] = $row ['NAME'];
			$i ++;
		}
		//print_r ( $listarr ); 
		return $listarr;
	}
	
	/**
	 * 记录交易历史
	 */
	function recordTransaction($productCode, $productName, $productPrice, $productPriceUnit, $count, $ammount, $platform) {
		$sqlstr = "INSERT INTO `transaction_history` ";
		$sqlstr .= "VALUES (NULL, '$this->accountId', '$productCode', ";
		$sqlstr .= "'$productName', '$count', '$productPrice', '$productPriceUnit', ";
		$sqlstr .= "'$ammount',  NOW(),'$platform' ) ";
		$this->db->Execute ( $sqlstr );
	}
	/**
	 * 
	 */
	function __destruct() {
	
	}

}
?>