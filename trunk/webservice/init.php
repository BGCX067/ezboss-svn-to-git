<?php 
if (!defined('IN_EZBOSS'))
{
    die('Hacking attempt');
}
require_once dirname(dirname(__FILE__)). './config/common.cfg.php';
require_once dirname(dirname(__FILE__)). './include/var.inc.php';
require_once dirname(dirname(__FILE__)). './include/lang.inc.php';
require_once EZBOSS_FUNCTIONS_PATH  .  './common.func.php';
require_once EZBOSS_CLASSES_PATH  .  './DataBase.class.php';
require_once EZBOSS_CLASSES_PATH  .  './BaseClass.class.php';
require_once EZBOSS_CLASSES_PATH  .  './Account.class.php';
require_once EZBOSS_CLASSES_PATH  .  './RechargeHistory.class.php';
require_once EZBOSS_CLASSES_PATH  .  './IPFilter.class.php';
require_once EZBOSS_CLASSES_PATH  .  './Product.class.php';
require_once EZBOSS_CLASSES_PATH  .  './ProductList.class.php';
require_once EZBOSS_CLASSES_PATH  .  './TransactionHistory.class.php';

session_start();
date_default_timezone_set('Asia/Shanghai'); //设置默认时区

?>