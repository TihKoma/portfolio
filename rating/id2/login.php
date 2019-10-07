<?php


$uid = $_POST['uid'];
$token = $_POST['token'];
//$uid = "1234567";

setcookie("bitrixId", $uid, time()+(3600*24),'/');
setcookie("token", $token, time()+(3600*24),'/');
echo "1";


?>