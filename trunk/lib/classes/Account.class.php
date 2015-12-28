<?php
/*
 * @desc:账户类
 * @$Id: Account.class.php
 */
class Account extends BaseClass {
	
	var $userId; //外部用户编号
	var $accountId; //账户编号
	/**
	 * 
	 */
	public function __construct($userId) {
		parent::__construct ();
		$this->userId = $userId;
		$this->accountId = $this->getAccountId ();
	}
	
	function getAccountId() {
		$sqlstr = "select ACCOUNT_ID from account act 
					where act.USER_ID='$this->userId' ";
		$rs = $this->db->Execute ( $sqlstr );
		//$rs = $this->db->GetRow( $sqlstr ); 
		if ($rs->RecordCount () == 0) {
			$sqlstr = "insert into account values(NULL, '$this->userId', 0 , 1)  ";
			$this->db->Execute ( $sqlstr );
			$sqlstr = "select ACCOUNT_ID from account act 
					where act.USER_ID='$this->userId' ";
			$row = $this->db->GetRow ( $sqlstr );
			return $row ['ACCOUNT_ID'];
		} else {
			$row = $rs->FetchRow ();
			return $row ['ACCOUNT_ID'];
		}
	}
	/** 
	 * 得到账户余额  
	 */
	function getBalance() {
		$sqlstr = "select BALANCE from account act 
					where ACCOUNT_ID='$this->accountId' ";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["BALANCE"];
	}
	/**
	 * 得到账户状态
	 */
	function getStatus() {
		$sqlstr = "select act.STATUS from account act
					where act.ACCOUNT_ID='$this->accountId' ";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["STATUS"];
	}
	/**
	 * 得到账户状态描述 
	 */
	public function getStatusDesc() {
		$status = $this->getStatus (); 
		$statusDesc = ''; 
		switch($status){
			case $GLOBALS['account_status']['normal']: $statusDesc = lang('account_status_normal'); break;
			case $GLOBALS['account_status']['freezed']: $statusDesc = lang('account_status_freezed'); break;
		} 
		return $statusDesc;
	}
	
	/**
	 * 更改账户状态 
	 * 返回 001:更改成功, 002:更改失败
	 */
	function updateStatus($status) {
		$ok = true;
		$this->db->StartTrans (); //事务块开始 
		$sqlstr = "update account act set act.STATUS = '$status' where act.ACCOUNT_ID = '$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		if (! $this->db->HasFailedTrans ()) //在事务块结束前捕获
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //事务块结束
		return $ok ? '001' : '002';
	}
	
	/**
	 * 账户充值 
	 * 返回 001:充值成功, 002:充值失败
	 */
	function addBalance($amount, $way) {
		$ok = true;
		$this->db->StartTrans (); //事务块开始
		$this->changeBalance ( $amount, $way );
		if (! $this->db->HasFailedTrans ()) //在事务块结束前捕获
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //事务块结束
		return $ok ? '001' : '002';
	}
	/**
	 * 账户充值
	 */
	function changeBalance($amount, $way) {
		$sqlstr = "update account act set BALANCE = BALANCE + '$amount' 
					where act.ACCOUNT_ID='$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		$sqlstr = "INSERT INTO recharge_history VALUES (NULL, '$this->accountId', '$amount', NOW(),'$way') ";
		$this->db->Execute ( $sqlstr );
	}
	
	/**
	 * 购买产品
	 * @param $productCode
	 * @param $count
	 * @param $platform
	 * 返回 001 成功，002失败，003余额不足，004账户已被冻结
	 */
	function buy($productCode, $count, $platform) {
		$ok = true;
		$product = new Product ( $productCode );
		$product = $product->getProduct ();
		$amount = $product ['price'] * $count;
		//检查状态
		if ($this->getStatus () == 2) {
			return '004';
		}
		//检查余额
		if ($this->getBalance () < $amount) {
			return '003';
		}
		$this->db->StartTrans (); //事务块开始
		//扣款 
		$sqlstr = "update account act set BALANCE = BALANCE - '$amount' 
					where ACCOUNT_ID='$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		//记录交易
		$transactionHistory = new TransactionHistory ( $this->accountId );
		$transactionHistory->recordTransaction ( $product ['productCode'], $product ['productName'], $product ['price'], $product ['priceUnit'], $count, $amount, $platform );
		//包月产品增加服务时间
		if ($product ['priceUnit'] > 1) {
			switch ($product ['priceUnit']) {
				case 2:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' DAY)';//天
				break;
				case 3:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . ($count * 7) . ' DAY)';//周
				break;
				case 4:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' MONTH)';//月
				break;
				case 5:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' YEAR)';//年
				break;				 
			}
			$sqlstr = "update service_time st set EXPIRY_DATE = $timeSql 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode' ";
			$this->db->Execute ( $sqlstr );
			if ($this->db->Affected_Rows () == 0) {
				//第一次购买 insert
				$sqlstr = "insert into service_time values(NULL, '$this->accountId','$productCode', NOW() )";
				$this->db->Execute ( $sqlstr );
				$sqlstr = "update service_time st set EXPIRY_DATE = $timeSql 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode' ";
				$this->db->Execute ( $sqlstr );
			}
		}
		if (! $this->db->HasFailedTrans ()) //在事务块结束前捕获
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //事务块结束
		return $ok ? '001' : '002';
	}
	
	/**
	 * 查询指定产品的服务截止间
	 * @param $productCode
	 */
	function getServiceTime($productCode) {
		$sqlstr = "select st.EXPIRY_DATE from service_time st 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode'";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["EXPIRY_DATE"];
	}
	
	/**
	 * 查询是否在产品的服务时间内
	 * @param $productCode
	 * 返回 001 在 002不在
	 */
	function isInService($productCode) {
		$sqlstr = "select count(*) as COUNT from service_time st 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode'";
		$sqlstr .= " and st.EXPIRY_DATE>= NOW()";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["COUNT"] == 1 ? '001' : '002';
	}
	
	/**
	 * 
	 */
	function __destruct() {
	
	}

}
?>