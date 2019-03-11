<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'activate'){
		$segment = $_POST['segment'];
		$balance = $_SESSION['balance'];
		if($balance < $segment){
			echo "Ошибка. Недостаточно средств.";
			return;
		};

		$balance -= $segment;
		$_SESSION['balance'] = $balance;
		
		$id = '\''.$_COOKIE['id'].'\'';
		$balance = '\''.$balance.'\'';
		$segment = '\''.$segment.'\'';
		
		$q = set_balance($id, $balance);

		// Если у родителя неактивирован gold ring, то идём выше и ищем того, у кого активирован.
		// echo $id.'-'.$pid;
		$pid = get_pid_gold_ring_active_connection($id, $_POST['segment']);


		// if($_POST['segment'] == 10)
			// $z = mysql_query("UPDATE tree_gold_ring SET status = 1 WHERE parent = $pid AND child = $id");
		// else
		$z = mysql_query("INSERT INTO tree_gold_ring (parent, child, status, segment) VALUES ($pid, $id, 1, $segment)");

		$id = $pid;
		$segment = $_POST['segment'];
		$pid = get_pid_gold_ring($id, $segment);
		$level = 1; //текущий уровень(1-6)

		// echo $id.'|'.$pid;
		while($pid != ''){
			$balance = get_balance($id) + switch_amount($level, $segment);
			$balance = '\''.$balance.'\'';
			set_balance($id, $balance);
			
			$level++;
			$id = $pid;
			$pid = get_pid_gold_ring($id, $segment);
		};
		


		$id = '\''.$_COOKIE['id'].'\'';
		$time = '\''.$_POST['time'].'\'';
		$segment = $_POST['segment'];

		$action = "Вход на \$$segment в Gold Ring";
		$action = '\''.$action.'\'';

		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, $action, $time)");

		if($q && $z && $m)
			echo "1";
		else
			echo "Ошибка";
	};


	function get_pid($id){
		$pid = '';
		$e = mysql_query("SELECT * FROM tree WHERE child = $id");
		if ($e != NULL)
			while($row = mysql_fetch_assoc($e))
				$pid = '\''.$row['parent'].'\''; //parent id
		return $pid;
	};

	function get_balance($id){
		$q = mysql_query("SELECT * FROM users WHERE id = $id");
		if ($q != NULL)
			while($row = mysql_fetch_assoc($q)){
				$balance = $row['balance'];
			};
		return $balance;
	};

	function set_balance($id, $balance){
		// echo $id.'|'.$balance;
		$t = mysql_query("UPDATE users SET balance = $balance WHERE id = $id");
		return $t;
	};

	// Возвращает id родителя из таблицы tree_gold_ring, либо ''.
	function get_pid_gold_ring($id, $segment = ''){
		// echo $id.'-'.$segment;
		$pid = '';
		if($segment == '')
			$e = mysql_query("SELECT * FROM tree_gold_ring WHERE child = $id");
		else
			$e = mysql_query("SELECT * FROM tree_gold_ring WHERE child = $id AND segment = $segment");

		if ($e != NULL)
			while($row = mysql_fetch_assoc($e))
				$pid = '\''.$row['parent'].'\''; //parent id
		return $pid;
	};


	// Возвращает сумму $, неоходимую для начисления на аккаунт, в зависимости от уровня и сегмента.
	function switch_amount($level, $segment){
		$amount = 0;
		switch($level){
			case 1:
				$amount = level1($segment);
				break;

			case 2:
				$amount = level2($segment);
				break;

			case 3:
				$amount = level3($segment);
				break;

			case 4:
				$amount = level4($segment);
				break;

			case 5:
				$amount = level5($segment);
				break;

			case 6:
				$amount = level6($segment);
				break;
		};

		return $amount;
	};

	// Возвращает сумму для 1-ого уровня и сегмента $segment.
	function level1($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 3;
				break;

			case 30:
				$amount = 9;
				break;

			case 50:
				$amount = 15;
				break;
		};
		return $amount;
	};

	// Возвращает сумму для 2-ого уровня и сегмента $segment.
	function level2($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 2;
				break;

			case 30:
				$amount = 6;
				break;

			case 50:
				$amount = 10;
				break;
		};
		return $amount;
	};

	// Возвращает сумму для 3-ого уровня и сегмента $segment.
	function level3($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 1;
				break;

			case 30:
				$amount = 3;
				break;

			case 50:
				$amount = 5;
				break;
		};
		return $amount;
	};

	// Возвращает сумму для 4-ого уровня и сегмента $segment.
	function level4($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 1;
				break;

			case 30:
				$amount = 3;
				break;

			case 50:
				$amount = 5;
				break;
		};
		return $amount;
	};

	// Возвращает сумму для 5-ого уровня и сегмента $segment.
	function level5($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 1;
				break;

			case 30:
				$amount = 3;
				break;

			case 50:
				$amount = 5;
				break;
		};
		return $amount;
	};

	// Возвращает сумму для 6-ого уровня и сегмента $segment.
	function level6($segment){
		$amount = 0;
		switch($segment){
			case 10:
				$amount = 1;
				break;

			case 30:
				$amount = 3;
				break;

			case 50:
				$amount = 5;
				break;
		};
		return $amount;
	};


	// Получить id пользователя, стоящего выше(родитель либо еще выше) 
	// и активировавшего gold ring в сегменте $segment.
	function get_pid_gold_ring_active_connection($id, $segment){
		$pid = '';
		$prev_id = '';
		
		while($pid == ''){
			$e = mysql_query("SELECT * FROM tree_gold_ring WHERE child = $id AND segment = $segment AND status = 1");
			if ($e != NULL)
				while($row = mysql_fetch_assoc($e))
					$pid = '\''.$row['parent'].'\''; //parent id

			$prev_id = $id; //Предыдущий id
			$id = get_pid_gold_ring($id); // Получаем id родителя, неактивировавшего gold ring
			if($id == '')
				break;
		};

		return $prev_id; //на уровень ниже
	};

?>