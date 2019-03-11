<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'update'){
		$id = $_COOKIE['id'];

		$surname = '\''.$_POST['surname'].'\'';
		$name = '\''.$_POST['name'].'\'';
		$patronymic = '\''.$_POST['patronymic'].'\'';
		$email = $_POST['email'];
		$skype = '\''.$_POST['skype'].'\'';

		$time = '\''.$_POST['time'].'\'';


		//Проверка на существование пользователя с логином $login и емейлом $email
		$res = mysql_query("SELECT * FROM users");
		if ($res != NULL) {
			while($row = mysql_fetch_assoc($res)) {
				if($row['email'] == $email && $row['id'] != $id)
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

		$email = '\''.$email.'\'';
		$id = '\''.$id.'\'';

		$q = mysql_query("UPDATE users SET surname = $surname, name = $name, patronymic = $patronymic, skype = $skype, email = $email WHERE id = $id");

		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, 'Изменение информации об аккаунте', $time)");

		if($q){
			unset($_SESSION['email']);
			echo "1";
			return;
		};
		echo "Ошибка";
	};

?>