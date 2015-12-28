<?php

class Product extends BaseClass {
	
	var $productCode; //²úÆ·´úÂë 
	/**
	 * 
	 */
	public function __construct($productCode) {
		parent::__construct ();
		$this->productCode = $productCode;
	}
	
	function getProduct() {
		$sqlstr = "select pdt.PRODUCT_CODE, pdt.PRODUCT_NAME, pdt.DESCRIPTION, ";
		$sqlstr .= "pdt.PRICE, pdt.PRICE_UNIT, pdt.STATUS "; 
		$sqlstr .= "from product pdt ";
		$sqlstr .= "where pdt.PRODUCT_CODE='$this->productCode' ";
		$row = $this->db->GetRow ( $sqlstr );
		$ary = array ();
		$ary ['productCode'] = $row ['PRODUCT_CODE'];
		$ary ['productName'] = $row ['PRODUCT_NAME'];
		$ary ['description'] = $row ['DESCRIPTION'];
		$ary ['price'] = $row ['PRICE']; 
		$ary ['priceUnit'] = $row ['PRICE_UNIT']; 
		switch($row ['PRICE_UNIT']){
			case $GLOBALS['product_unit']['one']: $ary ['unitDesc'] = lang('product_unit_one'); break; 
			case $GLOBALS['product_unit']['day']: $ary ['unitDesc'] = lang('product_unit_day'); break; 
			case $GLOBALS['product_unit']['week']: $ary ['unitDesc'] = lang('product_unit_week'); break; 
			case $GLOBALS['product_unit']['month']: $ary ['unitDesc'] = lang('product_unit_month'); break; 
			case $GLOBALS['product_unit']['year']: $ary ['unitDesc'] = lang('product_unit_year'); break; 
		}  
		$ary ['status'] = $row ['STATUS']; 
		switch($row ['STATUS_DESC']){
			case $GLOBALS['product_status']['available']: $ary ['statusDesc'] = lang('product_status_available'); break; 
			case $GLOBALS['product_status']['unavailable']: $ary ['statusDesc'] = lang('product_status_unavailable'); break;  
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