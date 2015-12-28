<?php
$client = new SoapClient ( "http://127.0.0.1/ezboss/webservice/ezboss.wsdl", array ('trace' => 1 ) );

$functions = $client->__getFunctions ();
//print_r ( $functions );
echo "<br>";
$types = $client->__getTypes ();
//print_r ( $types );
echo "<br>";

//测试查询用户账户余额，传入用户编号123456
$res = $client->querryUserBalance ( "123456" ); 
echo   'querryUserBalance='.$res ;
echo "<br>";
$res = $client->querryUserStatus ( "123456" ); 
 echo  'querryUserStatus='.$res ; 
echo "<br>";
$res = $client->querryUserStatusDesc ( "123456" ); 
 echo  'querryUserStatusDesc='.$res ; 
echo "<br>";
$res = $client->updateUserStatus ( "123456" , "1" ); //1正常 2冻结
echo   'updateUserStatus='.$res ;
echo "<br>";
//测试用户账户充值，传入用户编号123456，单位人民币分，充值通道1
$res = $client->addUserBalance ( "123456", "100", "1" );
echo  'addUserBalance='.$res  ;
echo "<br>";
$res = $client->buyProduct ("123456", "100004","3","1" ); 
 echo  'buyProduct='.$res ; 
echo "<br>";
$res = $client->querryServiceTime ("123456", "100002" ); 
 echo  'getServicquerryServiceTimeeTime='.$res ; 
echo "<br>";
$res = $client->querryIsInService ("123456", "100002"  ); 
 echo  'querryIsInService='.$res ; 
echo "<br>";

//测试查询产品列表
echo "<br>querryProductList-----------------------<br>";
$res = $client->querryProductList (); 
$ary =   ( $res );
print_r ( json_decode($ary )); 

//测试查询测试查询充值历史
echo "<br>querryUserRechargeHistory-----------------------<br>";
$res = $client->querryUserRechargeHistory ( "123456", "5", "1" );
//echo "Request :<br>", htmlspecialchars($client->__getLastRequest()), "<br>";
//echo "Response :<br>", htmlspecialchars($client->__getLastResponse()), "<br>"; 
$ary =   ( $res );
print_r ( json_decode($ary )); 

//测试查询测试查询交易历史
echo "<br>querryUserTransactionHistory-----------------------<br>";
$res = $client->querryUserTransactionHistory ( "123456", "5", "1" );  
$ary =   ( $res );
print_r ( json_decode($ary )); 
