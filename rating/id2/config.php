<?php

// $uid = $_COOKIE["userid"];
$uid = 12;
$token = 12342341;
// $token = $_COOKIE["token"];
if (!$uid) {
	$uid = 0;
}
$FirstName = "Юзер";
$LastName = "Юзерович";

$appid = "62";
$emid = "61";
$pass = "lqEA4Ig1JP";
$callbackurl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>