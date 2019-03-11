<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'output'){
		// $id = $_COOKIE['id'];

		$count = $_POST['count'];

		if($count > $_SESSION['balance'])
			die("Ошибка. Недостаточно средств.");

		$id = '\''.$_COOKIE['id'].'\'';
		$count = '\''.$count.'\'';

		$q = mysql_query("INSERT INTO output_money (uid, status, count) VALUES ($id, 0, $count)");

		if($q){
			echo "1";
			return;
		};
		echo "Ошибка";
	};

?>