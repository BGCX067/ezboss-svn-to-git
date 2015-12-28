<?php
define ( 'IN_EZBOSS', true );
 
ini_set ( "display_errors", 'on' );
error_reporting ( E_ERROR | E_PARSE );

require (dirname ( dirname ( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'webservice' . DIRECTORY_SEPARATOR . 'init.php');
//echo "test<br>";
//echo getonlineip();
//writelog("it is test log.");


$account = new Account ( '123456' );
echo "updateStatus=" . $account->updateStatus ("1");//1正常 2冻结
echo "<br>";
echo "getBalance=" . $account->getBalance ();
echo "<br>";
echo "getStatus=" .  $account->getStatus () ;
echo "<br>";
echo "getStatusDesc=" .  $account->getStatusDesc () ;
echo "<br>";
//测试用户账户充值  单位人民币分 
$res = $account->addBalance ( "10", "2" );
echo "addBalance=".$res;
echo "<br>";
$res = $account->buy ( "100004", "1", "1" ); 
echo "buy=".$res;
echo "<br>";
$res = $account->getServiceTime ( "100003"  ); 
echo "getServiceTime=".$res;
echo "<br>";
$res = $account->isInService ( "100003"  ); 
echo "isInService=".$res;
echo "<br>";

//测试查询产品列表
echo "<br>productList->getProducts-----------------------<br>";
$productList = new ProductList ( );
$res = $productList->getProducts ( );
print_r( $res);

echo "<br>";
echo "<br>rechargeHistory->getHistoryList-----------------------<br>";
//测试查询账户历史
$rechargeHistory = new RechargeHistory ( $account->accountId );
$res = $rechargeHistory->getHistoryList ( "10", "1" );
print_r( $res);

echo "<br>";
//测试查询测试查询交易历史
echo "<br>transactionHistory->getHistoryList-----------------------<br>";
$transactionHistory = new TransactionHistory ( $account->accountId );
$res = $transactionHistory->getHistoryList ( "10", "1" );
print_r( $res);