<?
require_once 'anet_php_sdk/AuthorizeNet.php'; // The SDK
$url = "http://www.webgrrlsinternational.info:8085/test.php";
$api_login_id = '7E5njJH63';
$transaction_key = '3vQyJ587Ku9n45pV';
$md5_setting = '7E5njJH63'; // Your MD5 Setting
$amount = "5.99";
AuthorizeNetDPM::directPostDemo($url, $api_login_id, $transaction_key, $amount, $md5_setting);
?>