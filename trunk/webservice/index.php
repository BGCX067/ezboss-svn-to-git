<?php
define ( 'IN_EZBOSS', true );

ini_set ( "display_errors", 'on' );
error_reporting ( E_ERROR | E_PARSE );

require (dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'init.php');

class EZBossWebService {
	
	/** 
	 * ��ѯ�û��˻����
	 * @param $userId
	 * ���� ����������λ����ҷ�
	 */
	public function querryUserBalance($userId) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$balance = $account->getBalance ();
		return $balance;
	}
	
	/** 
	 * ��ѯ�û��˻�״̬
	 * @param $userId
	 * ���� ״̬����
	 */
	public function querryUserStatus($userId) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return json_encode ( lang('ip_denied') );
		$account = new Account ( $userId );
		$status = $account->getStatus ();
		return $status;
	}
	
	/** 
	 * ��ѯ�û��˻�״̬����
	 * @param $userId
	 * ���� ״̬����
	 */
	public function querryUserStatusDesc($userId) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$statusDesc = $account->getStatusDesc ();
		return $statusDesc;
	}
	
	/** 
	 * �˻���ֵ
	 * @param $userId
	 * @param $ammount
	 * @param $note
	 * ���� 001:��ֵ�ɹ�, 002:��ֵʧ��
	 */
	public function addUserBalance($userId, $amount, $way) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$res = $account->addBalance ( $amount, $way );
		return $res;
	}
	
	/** 
	 * ��ѯ�û��˻���ֵ��ʷ
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * ����json����
	 */
	public function querryUserRechargeHistory($userId, $perPage, $currPage) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return json_encode ( lang('ip_denied') );
		$account = new Account ( $userId );
		$rechargeHistory = new RechargeHistory ( $account->accountId );
		return json_encode ( $rechargeHistory->getHistoryList ( $perPage, $currPage ) );
	}
	
	/**
	 * �����Ʒ
	 * @param $userId
	 * @param $product
	 * @param $count
	 * ���� 001 �ɹ���002ʧ�ܣ�003����
	 */
	public function buyProduct($userId, $product, $count, $platform) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$res = $account->buy ( $product, $count, $platform );
		return $res;
	}
	
	/**
	 * ��ѯָ����Ʒ�ķ����ֹ��
	 * @param $productCode
	 */
	public function querryServiceTime($userId, $product) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$res = $account->getServiceTime ( $product );
		return $res;
	}
	
	/**
	 * ��ѯ�Ƿ��ڲ�Ʒ�ķ���ʱ����
	 * @param $productCode
	 * ���� 001 �� 002����
	 */
	public function querryIsInService($userId, $product) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$res = $account->isInService ( $product );
		return $res;
	}
	
	/** 
	 * ��ѯ�û��˻�������ʷ
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * ����json����
	 */
	public function querryUserTransactionHistory($userId, $perPage, $currPage) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return json_encode ( lang('ip_denied') );
		$account = new Account ( $userId );
		$transactionHistory = new TransactionHistory ( $account->accountId );
		return json_encode ( $transactionHistory->getHistoryList ( $perPage, $currPage ) );
	}
	
	/** 
	 * ��ѯ��Ʒ�б�
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * ����json����
	 */
	public function querryProductList() {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return json_encode ( lang('ip_denied') );
		$productList = new ProductList ();
		return json_encode ( $productList->getProducts () );
	}
	
	/** 
	 * �޸��û�״̬
	 * @param $userId
	 * @param $status 
	 * ���� 001 �ɹ���002ʧ�� 
	 */
	public function updateUserStatus($userId, $status) {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return lang('ip_denied');
		$account = new Account ( $userId );
		$res = $account->updateStatus ( $status );
		return $res;
	}

}
$server = new SoapServer ( 'ezboss.wsdl', array ('soap_version' => SOAP_1_2 ) );
$server->setClass ( "EZBossWebService" );
$server->handle ();
