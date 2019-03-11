<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'update'){
		$id = $_POST['uid'];

		$login = $_POST['login'];
		$surname = '\''.$_POST['surname'].'\'';
		$name = '\''.$_POST['name'].'\'';
		$patronymic = '\''.$_POST['patronymic'].'\'';
		$email = $_POST['email'];
		$skype = '\''.$_POST['skype'].'\'';
		$advcash = '\''.$_POST['advcash'].'\'';

		$time = '\''.$_POST['time'].'\'';


		//Проверка на существование пользователя с логином $login и емейлом $email
		$res = mysql_query("SELECT * FROM users");
		if ($res != NULL) {
			while($row = mysql_fetch_assoc($res)) {
				if($row['email'] == $email && $row['id'] != $id)
					die("Ошибка. Пользователь с таким e-mail уже существует.");
				if($row['login'] == $login && $row['id'] != $id)
					die("Ошибка. Пользователь с таким логином уже существует.");
			};
		};


		$email = '\''.$email.'\'';
		$login = '\''.$login.'\'';
		$id = '\''.$id.'\'';

		$q = mysql_query("UPDATE users SET login = $login, surname = $surname, name = $name, patronymic = $patronymic, skype = $skype, email = $email, advcash = $advcash WHERE id = $id");

		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, 'Изменение информации об аккаунте', $time)");

		if($q){
			unset($_SESSION['email']);
			echo "1";
			return;
		};
		echo "Ошибка";
	};

?>