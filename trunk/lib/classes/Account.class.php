<?php
/*
 * @desc:�˻���
 * @$Id: Account.class.php
 */
class Account extends BaseClass {
	
	var $userId; //�ⲿ�û����
	var $accountId; //�˻����
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
	 * �õ��˻����  
	 */
	function getBalance() {
		$sqlstr = "select BALANCE from account act 
					where ACCOUNT_ID='$this->accountId' ";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["BALANCE"];
	}
	/**
	 * �õ��˻�״̬
	 */
	function getStatus() {
		$sqlstr = "select act.STATUS from account act
					where act.ACCOUNT_ID='$this->accountId' ";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["STATUS"];
	}
	/**
	 * �õ��˻�״̬���� 
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
	 * �����˻�״̬ 
	 * ���� 001:���ĳɹ�, 002:����ʧ��
	 */
	function updateStatus($status) {
		$ok = true;
		$this->db->StartTrans (); //����鿪ʼ 
		$sqlstr = "update account act set act.STATUS = '$status' where act.ACCOUNT_ID = '$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		if (! $this->db->HasFailedTrans ()) //����������ǰ����
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //��������
		return $ok ? '001' : '002';
	}
	
	/**
	 * �˻���ֵ 
	 * ���� 001:��ֵ�ɹ�, 002:��ֵʧ��
	 */
	function addBalance($amount, $way) {
		$ok = true;
		$this->db->StartTrans (); //����鿪ʼ
		$this->changeBalance ( $amount, $way );
		if (! $this->db->HasFailedTrans ()) //����������ǰ����
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //��������
		return $ok ? '001' : '002';
	}
	/**
	 * �˻���ֵ
	 */
	function changeBalance($amount, $way) {
		$sqlstr = "update account act set BALANCE = BALANCE + '$amount' 
					where act.ACCOUNT_ID='$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		$sqlstr = "INSERT INTO recharge_history VALUES (NULL, '$this->accountId', '$amount', NOW(),'$way') ";
		$this->db->Execute ( $sqlstr );
	}
	
	/**
	 * �����Ʒ
	 * @param $productCode
	 * @param $count
	 * @param $platform
	 * ���� 001 �ɹ���002ʧ�ܣ�003���㣬004�˻��ѱ�����
	 */
	function buy($productCode, $count, $platform) {
		$ok = true;
		$product = new Product ( $productCode );
		$product = $product->getProduct ();
		$amount = $product ['price'] * $count;
		//���״̬
		if ($this->getStatus () == 2) {
			return '004';
		}
		//������
		if ($this->getBalance () < $amount) {
			return '003';
		}
		$this->db->StartTrans (); //����鿪ʼ
		//�ۿ� 
		$sqlstr = "update account act set BALANCE = BALANCE - '$amount' 
					where ACCOUNT_ID='$this->accountId' ";
		$this->db->Execute ( $sqlstr );
		//��¼����
		$transactionHistory = new TransactionHistory ( $this->accountId );
		$transactionHistory->recordTransaction ( $product ['productCode'], $product ['productName'], $product ['price'], $product ['priceUnit'], $count, $amount, $platform );
		//���²�Ʒ���ӷ���ʱ��
		if ($product ['priceUnit'] > 1) {
			switch ($product ['priceUnit']) {
				case 2:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' DAY)';//��
				break;
				case 3:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . ($count * 7) . ' DAY)';//��
				break;
				case 4:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' MONTH)';//��
				break;
				case 5:$timeSql = 'DATE_ADD(EXPIRY_DATE,INTERVAL ' . $count . ' YEAR)';//��
				break;				 
			}
			$sqlstr = "update service_time st set EXPIRY_DATE = $timeSql 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode' ";
			$this->db->Execute ( $sqlstr );
			if ($this->db->Affected_Rows () == 0) {
				//��һ�ι��� insert
				$sqlstr = "insert into service_time values(NULL, '$this->accountId','$productCode', NOW() )";
				$this->db->Execute ( $sqlstr );
				$sqlstr = "update service_time st set EXPIRY_DATE = $timeSql 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode' ";
				$this->db->Execute ( $sqlstr );
			}
		}
		if (! $this->db->HasFailedTrans ()) //����������ǰ����
			$ok = true;
		else
			$ok = false;
		$this->db->CompleteTrans (); //��������
		return $ok ? '001' : '002';
	}
	
	/**
	 * ��ѯָ����Ʒ�ķ����ֹ��
	 * @param $productCode
	 */
	function getServiceTime($productCode) {
		$sqlstr = "select st.EXPIRY_DATE from service_time st 
					where st.ACCOUNT_ID='$this->accountId' and st.PRODUCT_CODE='$productCode'";
		$row = $this->db->GetRow ( $sqlstr );
		return $row ["EXPIRY_DATE"];
	}
	
	/**
	 * ��ѯ�Ƿ��ڲ�Ʒ�ķ���ʱ����
	 * @param $productCode
	 * ���� 001 �� 002����
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