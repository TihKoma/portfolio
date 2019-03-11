<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();
	
	if(isset($_POST['action']) && $_POST['action'] == 'delete'){

		if(!isset($_POST['mas']) || !$_POST['mas'] || (!isset($_POST['count']))){
			echo "-1";
			return;
		};

		$mas = $_POST['mas'];
		$count = $_POST['count'];
		$sum = 0;

		for($i = 0; $i < count($mas); $i++){
			$id = '\''.$mas[$i].'\'';
			$q = mysql_query("DELETE FROM input_money WHERE id = $id");
			if($q != NULL)
				$sum += 1;
		};
		if($sum == $count)
			echo "1";
		else
			echo "Ошибка удаления";

	} else {
		if(!isset($_POST['uid']) || !$_POST['uid'] || (!isset($_POST['count'])))
			die("Ошибка данных.");

		$id = '\''.$_POST['id'].'\''; //id записи
		$uid = '\''.$_POST['uid'].'\'';
		$count = $_POST['count'];
		$time = '\''.$_POST['time'].'\'';

		$balance = get_balance($uid);
		$balance = +$balance + +$count;
		$balance = '\''.$balance.'\'';

		$q = mysql_query("UPDATE users SET balance = $balance WHERE id = $uid");

		$t = mysql_query("UPDATE input_money SET status = 1 WHERE id = $id");

		$login = get_login($uid);
		$action = "Подтверждён запрос на ввод $. На аккаунт $login начислено \$$count";
		$action = '\''.$action.'\'';
		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES (1, $action, $time)");

		if($q && $t && $m)
			echo "1";
		else
			echo "Ошибка";
	};


	function get_balance($id){
		$q = mysql_query("SELECT * FROM users WHERE id = $id");
		if ($q != NULL)
			while($row = mysql_fetch_assoc($q)){
				$balance = $row['balance'];
			};
		return $balance;
	};

	function get_login($id){
		$login = '';
		$t = mysql_query("SELECT * FROM users WHERE id = $id");
		if ($t != NULL)
			while($row = mysql_fetch_assoc($t)){
				$login = $row['login'];
			};
		return $login;
	};

?>