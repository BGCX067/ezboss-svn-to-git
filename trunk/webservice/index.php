<?php
define ( 'IN_EZBOSS', true );

ini_set ( "display_errors", 'on' );
error_reporting ( E_ERROR | E_PARSE );

require (dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'init.php');

class EZBossWebService {
	
	/** 
	 * 查询用户账户余额
	 * @param $userId
	 * 返回 正整数，单位人民币分
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
	 * 查询用户账户状态
	 * @param $userId
	 * 返回 状态代码
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
	 * 查询用户账户状态描述
	 * @param $userId
	 * 返回 状态描述
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
	 * 账户充值
	 * @param $userId
	 * @param $ammount
	 * @param $note
	 * 返回 001:充值成功, 002:充值失败
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
	 * 查询用户账户充值历史
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * 返回json数据
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
	 * 购买产品
	 * @param $userId
	 * @param $product
	 * @param $count
	 * 返回 001 成功，002失败，003余额不足
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
	 * 查询指定产品的服务截止间
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
	 * 查询是否在产品的服务时间内
	 * @param $productCode
	 * 返回 001 在 002不在
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
	 * 查询用户账户消费历史
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * 返回json数据
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
	 * 查询产品列表
	 * @param $userId
	 * @param $perPage
	 * @param $currPage
	 * 返回json数据
	 */
	public function querryProductList() {
		$ip = new IPFilter ( getonlineip () );
		if ($ip->isAllowable () != 1)
			return json_encode ( lang('ip_denied') );
		$productList = new ProductList ();
		return json_encode ( $productList->getProducts () );
	}
	
	/** 
	 * 修改用户状态
	 * @param $userId
	 * @param $status 
	 * 返回 001 成功，002失败 
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
