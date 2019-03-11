<?php
	header("Content-type: text/html; charset=utf-8");
	include('model.php');

	startup();

	if($_POST['action'] == 'register'){
		$surname = '\''.$_POST['surname'].'\'';
		$name = '\''.$_POST['name'].'\'';
		$patronymic = '\''.$_POST['patronymic'].'\'';
		$login = $_POST['login'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$skype = '\''.$_POST['skype'].'\'';
		$advcash = '\''.$_POST['advcash'].'\'';
		$time = '\''.$_POST['time'].'\'';
		// $parent = ($_POST['parent'] == '') ? "calipso1703" : $_POST['parent'];
		$parent = $_POST['parent'];
		$parent = '\''.$parent.'\'';

		$password = '\''.md5($password).'\'';

		// echo $surname.' '.$name.' '.$patronymic.' '.$login.' '.$password.' '.$email;


		//Проверка на существование пользователя с логином $login и емейлом $email
		$res = mysql_query("SELECT * FROM users");
		if ($res != NULL) {
			while($row = mysql_fetch_assoc($res)) {
				if($row['login'] == $login)
					die("Ошибка. Пользователь с таким логином уже существует.");

				if($row['email'] == $email)
					die("Ошибка. Пользователь с таким e-mail уже существует.");
			};
		};

		// echo $email;

		//Проверка на существование пользователя с е-мейлом $email
		// $q = mysql_query("SELECT * FROM users WHERE email = $email");
		// if ($q != NULL) {
		// 	// while($row = mysql_fetch_assoc($res)) {

		// 	// };
		// 	die("Ошибка. Пользователь с таким e-mail уже существует.");
		// };

		// //Проверка на существование пользователя с логином $login
		// $q = mysql_query("SELECT * FROM users WHERE login = $login");
		// if ($q != NULL) {
		// 	die("Ошибка. Пользователь с таким логином уже существует.");
		// };

		$login = '\''.$login.'\'';
		$email = '\''.$email.'\'';

		$q = mysql_query("INSERT INTO users (login, email, password, surname, name, patronymic, skype, advcash) VALUES ($login, $email, $password, $surname, $name, $patronymic, $skype, $advcash)");

		$id = '\''.mysql_insert_id().'\'';
		$val = 0;

		$z = mysql_query("INSERT INTO tables (id, start, main, leader, vip, 1nd, 2nd) VALUES ($id, $val, $val, $val, $val, $val, $val)");

		$pid = '';
		$m = mysql_query("SELECT * FROM users WHERE login = $parent");
		if ($m != NULL)
			while($row = mysql_fetch_assoc($m))
				$pid = $row['id']; //parent id

		if($pid == '')
			$pid = '72';
		// set_child($pid, $id);
		$z = mysql_query("INSERT INTO tree_gold_ring (parent, child, status, segment) VALUES ($pid, $id, '*', 10)");



		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, 'Регистрация на сайте', $time)");

		if($q && $z && $m){
			echo "1";
			return;
		};
		echo "Ошибка";
	};

	

	// function set_child($parent, $uid){
	// 	if(get_count_child($parent) < 3){
	// 		// echo "123";
	// 		$m = mysql_query("INSERT INTO tree (parent, child, status, table_num) VALUES ($parent, $uid, 0, 0)");
	// 		return;
	// 	};

	// 	$i = 0;
	// 	$mas = array($parent); //массив с id родителей одного уровня
	// 	$bol = 1;
	// 	// echo $mas;

	// 	while($bol){
	// 		for($j = 0; $j < count($mas); $j++){
	// 			// echo "string";
	// 			if(get_count_child($mas[$j]) < 3){
	// 				$bol = 0;
	// 				$m = mysql_query("INSERT INTO tree (parent, child, status, table_num) VALUES ($mas[$j], $uid, 0, 0)");
	// 				break;
	// 			};
	// 		};

	// 		$mas = get_child_id($mas);
	// 	};
	// };

	function get_count_child($id){
		$cnt = mysql_fetch_row(mysql_query("SELECT count(*) FROM tree WHERE parent = $id AND status = 1"));
		$cnt = $cnt[0];
		return $cnt;
	};

	// Возвращает массив с детьми
	// $mas,  $arr - массивы с id пользователей
	function get_child_id($arr){
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

?>