<?php

class ProductList extends BaseClass {
	
	/**
	 * 
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	function getProducts() {
		$sqlstr = "select pdt.PRODUCT_CODE, pdt.PRODUCT_NAME, pdt.DESCRIPTION, ";
		$sqlstr .= "pdt.PRICE, pdt.PRICE_UNIT, pdt.STATUS "; 
		$sqlstr .= "from product pdt ";
		$rs = $this->db->Execute ( $sqlstr );
		//$num = $rs->RecordCount();		 
		$ary = array ();
		$i = 0;
		foreach ( $rs as $row ) { 
			$ary ['productCode'] = $row ['PRODUCT_CODE'];
			$ary ['productName'] = $row ['PRODUCT_NAME'];
			$ary ['description'] = $row ['DESCRIPTION'];
			$ary ['price'] = $row ['PRICE']; 
			$ary ['priceUnit'] = $row ['PRICE_UNIT']; 
			switch($row ['PRICE_UNIT']){
				case $GLOBALS['product_unit']['one']: $ary ['priceUnit'] = lang('product_unit_one'); break; 
				case $GLOBALS['product_unit']['day']: $ary ['priceUnit'] = lang('product_unit_day'); break; 
				case $GLOBALS['product_unit']['week']: $ary ['priceUnit'] = lang('product_unit_week'); break; 
				case $GLOBALS['product_unit']['month']: $ary ['priceUnit'] = lang('product_unit_month'); break; 
				case $GLOBALS['product_unit']['year']: $ary ['priceUnit'] = lang('product_unit_year'); break; 
			} 
			$ary ['unitDesc'] = $row ['UNIT_DESC'];
			$ary ['status'] = $row ['STATUS']; 
			switch($row ['STATUS_DESC']){
				case $GLOBALS['product_status']['available']: $ary ['statusDesc'] = lang('product_status_available'); break; 
				case $GLOBALS['product_status']['unavailable']: $ary ['statusDesc'] = lang('product_status_unavailable'); break;  
			} 
			$i ++;
		}
		return $ary;
	}
	
	/**
	 * 
	 */
	function __destruct() {
	
	}

}
?>