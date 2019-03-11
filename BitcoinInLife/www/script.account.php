<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'activate'){
		$balance = $_SESSION['balance'];
		if($balance < 123){
			echo "Ошибка. Недостаточно средств.";
			return;
		};

		$balance -= 123;
		$_SESSION['balance'] = $balance;
		$_SESSION['start'] = 1;
		
		$id = '\''.$_COOKIE['id'].'\'';
		$balance = '\''.$balance.'\'';
		
		// $q = mysql_query("UPDATE users SET balance = $balance WHERE id = $id");
		$q = set_balance($id, $balance);
		$z = mysql_query("UPDATE tables SET start = '1' WHERE id = $id");

		// $m = mysql_query("UPDATE tree SET status = 1, table_num = 1 WHERE child = $id");

		$pid = get_pid_active_connection($id);
		set_child($pid, $id);

		// // Получаем id родителя
		// $e = mysql_query("SELECT * FROM tree WHERE child = $id");
		// if ($e != NULL)
		// 	while($row = mysql_fetch_assoc($e))
		// 		$pid = '\''.$row['parent'].'\''; //parent id

		// // Получаем столы родителя
		// $m = mysql_query("SELECT * FROM tables WHERE id = $pid");
		// if ($m != NULL)
		// 	while($row = mysql_fetch_assoc($m)){
		// 		$start = $row['start'];
		// 		$main = $row['main'];
		// 		// $leader = $row['leader'];
		// 		// $vip = $row['vip'];
		// 		// $1nd = $row['1nd'];
		// 		// $2nd = $row['2nd'];
		// 	};

		// // Получаем кол-во активных связей 1-ого блока родителя
		// $cnt = mysql_query("SELECT count(*) FROM tree WHERE parent = $pid AND status = 1 AND block1 = 1");

		// // Если открыт стартовый стол и он заполнен, открываем родителю основной стол и закрываем стартовый.
		// if(($cnt == 3) && ($start == 1))
		// 	$t = mysql_query("UPDATE tables SET start = '0', main = '1' WHERE id = $pid");


		// // Получаем id родителя родителя
		// $e = mysql_query("SELECT * FROM tree WHERE child = $pid");
		// if ($e != NULL){
		// 	while($row = mysql_fetch_assoc($e))
		// 		$ppid = '\''.$row['parent'].'\''; //parent parent id

			
		// 	// Получаем кол-во активных связей 1-ого блока родителя родителя
		// 	$pp_cnt = mysql_query("SELECT count(*) FROM tree WHERE parent = $ppid AND status = 1 AND block1 = 1");
		// 	if($pp_cnt == 3){
		// 		$pp_child_id = array(); //parent parent child id
		// 		$i = 0;
		// 		//Получаем все(3) активные связи 1-ого блока родителя родителя(получаем id детей)
		// 		$k = mysql_query("SELECT * FROM tree WHERE parent = $ppid AND status = 1 AND block1 = 1");
		// 		if ($k != NULL)
		// 			while($row = mysql_fetch_assoc($k)){
		// 				$pp_child_id[$i] = '\''.$row['child'].'\'';
		// 				$i++;
		// 			};
		// 		// Получаем кол-во активных связей 3-х детей
		// 		$cnt1 = mysql_query("SELECT count(*) FROM tree WHERE parent = $pp_child_id[1] AND status = 1 AND block1 = 1");
		// 		$cnt2 = mysql_query("SELECT count(*) FROM tree WHERE parent = $pp_child_id[2] AND status = 1 AND block1 = 1");
		// 		$cnt3 = mysql_query("SELECT count(*) FROM tree WHERE parent = $pp_child_id[3] AND status = 1 AND block1 = 1");
		// 		if(($cnt1 + $cnt2 + $cnt3) == 9){

		// 		};
		// 	};
		// };


		$pid = '';
		$table = 1; //текущий рассматриваемый стол
		$p_child_id = array(); //parent child id
		$i = 0;
		// Получаем id родителя
		$pid = get_pid($id);

		while($pid != ''){
			// Получаем столы родителя
			$m = mysql_query("SELECT * FROM tables WHERE id = $pid");
			if ($m != NULL)
				while($row = mysql_fetch_assoc($m)){
					$start = $row['start'];
					$main = $row['main'];
					$leader = $row['leader'];
					$vip = $row['vip'];
					$nd1 = $row['1nd'];
					$nd2 = $row['2nd'];
				};

			if($table > 2)
				update_main($pid);
			if($table > 3)
				update_leader($pid);

			// Получаем кол-во активных связей родителя(получаем кол-во детей родителя)
			$cnt = get_count_child($pid, $table, 1);

			
			if(($cnt == 3) && ($table == 1 || $table == 3 || $table == 5))
				$table = set_table($pid, $table);
			
			if($cnt == 3){
				// Получаем все(3) активные связи родителя(получаем id детей)
				$p_child_id = get_child_id($pid, $table, 1);
				
				// Получаем кол-во активных связей 3-х детей
				$cnt1 = get_count_child($p_child_id[0], $table, 1);
				$cnt2 = get_count_child($p_child_id[1], $table, 1);
				$cnt3 = get_count_child($p_child_id[2], $table, 1);

				if(($cnt1 + $cnt2 + $cnt3) == 9){
					if($table == 2){
						save_round_main($pid);
						$t = mysql_query("UPDATE tables SET leader='1' WHERE id = $pid");
						$balance = get_balance($pid) + 615;
						set_balance($pid, $balance);
						// Меняем состояние связи для родителя, где он - ребенок
						$m = mysql_query("UPDATE tree SET round = 2 WHERE child = $pid");

						$ppid = get_pid($pid); // parent parent id
						if($ppid != '')
							$m = mysql_query("INSERT INTO tree (parent, child, status, table_num, round) VALUES ($ppid, $pid, 1, 3, 1)");
						
						$table = 3;
					} else if($table == 4){

						//Кол-во завершенных vip столов
						$t = mysql_query("SELECT * FROM tables WHERE id = $pid");
						if ($t != NULL)
							while($row = mysql_fetch_assoc($t)){
								$cnt_vip = $row['count_vip'];
							};

						if($cnt_vip == 1){
							$t = mysql_query("UPDATE tables SET vip='0', leader='1', 1nd='1' WHERE id=$pid");
							// Меняем состояние связи для родителя, где он - ребенок
							$m = mysql_query("UPDATE tree SET table_num = '5' WHERE child = $pid");
							$table = 5;
						} else {
							$t = mysql_query("UPDATE tables SET vip='0', leader='1', count_vip='1' WHERE id=$pid");
							$t = mysql_query("UPDATE tree SET status = '0' WHERE parent = $pid AND table_num = '4'");
						};

						$balance = get_balance($pid) + 3690;
						set_balance($pid, $balance);
					} else if($table == 6){
						$t = mysql_query("UPDATE tables SET 1nd='1', 2nd='0' WHERE id=$pid");
						$t = mysql_query("UPDATE tree SET status = '0' WHERE parent = $pid AND table_num = '6'");
						$m = mysql_query("UPDATE tree SET table_num = 6 WHERE child = $pid");
						$balance = get_balance($pid) + 369000;
						set_balance($pid, $balance);
					};
				};

				$id = $pid;
				$pid = get_pid($id); // Получаем id родителя
				continue;
			};
			$pid = '';
		};

		


		$id = '\''.$_COOKIE['id'].'\'';
		$time = '\''.$_POST['time'].'\'';
		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, 'Активация стартового стола', $time)");

		if($q && $z &&$m)
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
		$t = mysql_query("UPDATE users SET balance = $balance WHERE id = $id");
		return $t;
	};

	//$block - номер блока для поиска кол-ва связей(1,2,3)
	function get_count_child($id, $table, $round){
		if($id == '')
			return 0;
		$cnt = mysql_query("SELECT count(*) FROM tree WHERE parent = $id AND status = 1 AND table_num = $table AND round = $round");
		if($cnt != NULL){
			$count = mysql_fetch_array($cnt);
			return $count[0];
		};
		return 0;
	};

	function set_table($id, $table){
		if($table == 1){
			$t = mysql_query("UPDATE tables SET start = '0', main = '1' WHERE id = $id");
			$t = mysql_query("UPDATE tree SET table_num='2' WHERE child = $id");
			$balance = get_balance($id) + 123;
			set_balance($id, $balance);
			$table = 2;
		};
		if($table == 3){
			//Кол-во завершенных лидерских столов
			$t = mysql_query("SELECT * FROM tables WHERE id = $id");
			if ($t != NULL)
				while($row = mysql_fetch_assoc($t)){
					$cnt_leader = $row['count_leader'];
				};

			// if($cnt_leader == 1){
			// 	$t = mysql_query("UPDATE tables SET leader = '0', vip = '1' WHERE id = $id");
			// 	$t = mysql_query("UPDATE tree SET table_num='4' WHERE child = $id AND table_num = '3'");
			// 	$table = 4;
			// } else {
			// 	// $t = mysql_query("UPDATE tree SET status = '0' WHERE parent = $id AND table_num='3'");
			// 	$t = mysql_query("UPDATE tree SET status = '0' WHERE parent = $id");
			// 	$t = mysql_query("UPDATE tables SET leader = '0', count_leader = '1' WHERE id = $id");
			// 	$table = 3;
			// };

			// $t = mysql_query("UPDATE tree SET status = '0' WHERE parent = $id AND table_num='3'");
			$t = mysql_query("UPDATE tree SET status = '0' WHERE child = $id");
			$t = mysql_query("UPDATE tables SET leader = '0', count_leader = '1' WHERE id = $id");
			$table = 4;
			
			$balance = get_balance($id) + 1230;
			set_balance($id, $balance);
		};
		if($table == 5){
			$t = mysql_query("UPDATE tables SET 1nd = '0', 2nd = '1' WHERE id = $id");
			$t = mysql_query("UPDATE tree SET table_num='6' WHERE child = $id");
			$balance = get_balance($id) + 24600;
			set_balance($id, $balance);
			$table = 6;
		};

		return $table;
	};

	// Возвращает массив с детьми
	function get_child_id($id, $table, $round){
		$i = 0;
		$mas = array();

		$k = mysql_query("SELECT * FROM tree WHERE parent = $id AND status = 1 AND table_num = $table AND round = $round");

		if ($k != NULL)
			while($row = mysql_fetch_assoc($k)){
				$mas[$i] = '\''.$row['child'].'\'';
				$i++;
			};
		return $mas;
	};

// Функция, отвечающая за реинвесты в столе main
	function update_main($id){
		save_round_main($id); //не протестировано

		$p_child_id = array(); //parent child id

		$round = get_round($id, 2);

		$cnt = get_count_child($id, 2, $round);
		if($cnt < 3)
			return;

		// Получаем все(3) активные связи родителя(получаем id детей)
		$p_child_id = get_child_id($id, 2, $round);
				
		// Получаем кол-во активных связей 3-х детей
		$cnt1 = get_count_child($p_child_id[0], 2, $round);
		$cnt2 = get_count_child($p_child_id[1], 2, $round);
		$cnt3 = get_count_child($p_child_id[2], 2, $round);

		if(($cnt1 + $cnt2 + $cnt3) == 9){
			$round++;
			$m = mysql_query("UPDATE tree SET round = $round WHERE child = $id AND table_num = 2");
			$balance = get_balance($id) + 615;
			set_balance($id, $balance);

			// Создание следующего круга в столе leader
			$round = get_round($id, 3);
			$round++;
			$k = mysql_query("UPDATE tree SET status = 1, round = $round WHERE child = $id AND table_num = 3");
		};
	};

	function get_round($id, $table){
		$round = '';

		$k = mysql_query("SELECT * FROM tree WHERE child = $id AND table_num = $table");
		if ($k != NULL)
			while($row = mysql_fetch_assoc($k))
				$round = $row['round'];
		return $round;
	};

	// Функция, отвечающая за реинвесты в столе leader
	function update_leader($id){

		$round = get_round($id, 3);

		$cnt = get_count_child($id, 3, $round);
		if($cnt < 3)
			return;

		$round++;
		$m = mysql_query("UPDATE tree SET round = $round WHERE child = $id AND table_num = 3");
		$balance = get_balance($id) + 1230;
		set_balance($id, $balance);
	};

	function save_round_main($id){
		$round = get_round($id, 2);
		$child = array('', '', '');
		$i = 0;
		$child = get_child_id($id, 2, $round);

		$child_child1 = array('', '', '');
		$child_child2 = array('', '', '');
		$child_child3 = array('', '', '');
		
		$child_child1 = get_child_id($child[0], 2, $round);
		$child_child2 = get_child_id($child[1], 2, $round);
		$child_child3 = get_child_id($child[2], 2, $round);

		$q = mysql_query("INSERT INTO saved_rounds (uid, table_num, round, child1, child2, child3, child1_child1, child1_child2, child1_child3, child2_child1, child2_child2, child2_child3, child3_child1, child3_child2, child3_child3) VALUES ($id, 2, $round, $child[0], $child[1], $child[2], $child_child1[0], $child_child1[1], $child_child1[2], $child_child2[0], $child_child2[1], $child_child2[2], $child_child3[0], $child_child3[1], $child_child3[2])");
	};



	// Возвращает id родителя из таблицы tree_gold_ring, либо ''.
	function get_pid_gold_ring($id, $segment){
		$pid = '';
		$e = mysql_query("SELECT * FROM tree_gold_ring WHERE child = $id AND segment = $segment");
		if ($e != NULL)
			while($row = mysql_fetch_assoc($e))
				$pid = '\''.$row['parent'].'\''; //parent id
		return $pid;
	};

	// Запись связи родитель-потомок в таблице tree основной программы.
	function set_child($parent, $uid){
		if(register_get_count_child($parent) < 3){
			// echo "123";
			$m = mysql_query("INSERT INTO tree (parent, child, status, table_num) VALUES ($parent, $uid, 1, 1)");
			return;
		};

		$i = 0;
		$mas = array($parent); //массив с id родителей одного уровня
		$bol = 1;
		// echo $mas;

		while($bol){
			for($j = 0; $j < count($mas); $j++){
				// echo "string";
				if(register_get_count_child($mas[$j]) < 3){
					$bol = 0;
					$m = mysql_query("INSERT INTO tree (parent, child, status, table_num) VALUES ($mas[$j], $uid, 1, 1)");
					break;
				};
			};

			$mas = register_get_child_id($mas);
		};
	};

	// Для записи в таблицу tree
	function register_get_count_child($id){
		$cnt = mysql_fetch_row(mysql_query("SELECT count(*) FROM tree WHERE parent = $id AND status = 1"));
		$cnt = $cnt[0];
		return $cnt;
	};

	// Возвращает массив с детьми
	// $mas,  $arr - массивы с id пользователей
	function register_get_child_id($arr){
		$i = 0;
		$mas = array();
		
		for($i = 0; $i < count($arr); $i++){
			$k = mysql_query("SELECT * FROM tree WHERE parent = $arr[$i] AND status = 1");
			if ($k != NULL)
			while($row = mysql_fetch_assoc($k)){
				$mas[$i] = '\''.$row['child'].'\'';
				$i++;
			};
		};
		
		return $mas;
	};

	// Получить id пользователя, стоящего выше(родитель либо еще выше) 
	// и активировавшего стартовый стол.
	function get_pid_active_connection($id){
		$id = get_pid_real_parent($id); // Получаем id реального родителя

		$pid = '';
		$prev_id = '';

	// Получаем pid родителя активировавшего стартовый стол.
		while($pid == ''){
			$e = mysql_query("SELECT * FROM tree WHERE child = $id AND status = 1");
			if ($e != NULL)
				while($row = mysql_fetch_assoc($e))
					$pid = '\''.$row['parent'].'\''; //parent id

			$prev_id = $id; //Предыдущий id
			$id = get_pid_real_parent($id); // Получаем id родителя
			if($id == '')
				break;
		};

		return $prev_id; //на уровень ниже
	};

	// Возвращает pid реального родителя.
	function get_pid_real_parent($id){
		$pid = '';
		$e = mysql_query("SELECT * FROM tree_gold_ring WHERE child = $id AND segment = 10 AND status = '*'");
		if ($e != NULL)
			while($row = mysql_fetch_assoc($e))
				$pid = '\''.$row['parent'].'\''; //parent id

		return $pid;
	};

?>