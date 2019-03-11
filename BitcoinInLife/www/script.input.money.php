<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();
	
	if(!isset($_POST['card']) || !isset($_POST['cnt']) || (!isset($_POST['payment'])) || (!isset($_POST['time'])))
		die("Ошибка данных.");

	$uid = '\''.$_COOKIE['id'].'\''; //id пользователя
	$count = $_POST['cnt'];
	$time = '\''.$_POST['time'].'\'';
	$payment = '\''.$_POST['payment'].'\'';
	$card = '\''.$_POST['card'].'\'';


	$q = mysql_query("INSERT INTO input_money (uid, payment, card, count, time) VALUES ($uid, $payment, $card, $count, $time)");

	if($q)
		echo "1";
	else
		echo "Ошибка";


	// function get_balance($id){
	// 	$q = mysql_query("SELECT * FROM users WHERE id = $id");
	// 	if ($q != NULL)
	// 		while($row = mysql_fetch_assoc($q)){
	// 			$balance = $row['balance'];
	// 		};
	// 	return $balance;
	// };

	// function get_login($id){
	// 	$login = '';
	// 	$t = mysql_query("SELECT * FROM users WHERE id = $id");
	// 	if ($t != NULL)
	// 		while($row = mysql_fetch_assoc($t)){
	// 			$login = $row['login'];
	// 		};
	// 	return $login;
	// };

?>