<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'update'){

		$id = $_COOKIE['id'];
		$old_password = md5($_POST['old_password']);
		$password = '\''.md5($_POST['password']).'\'';

		$time = '\''.$_POST['time'].'\'';

		//Проверка совпадения пароля
		$q = mysql_query("SELECT * FROM users WHERE id = $id");
		if ($q != NULL) {
			while($row = mysql_fetch_assoc($q)) {
				if($row['password'] != $old_password)
					die("Ошибка. Старый пароль введён неправильно.");
			};
		};

		$id = '\''.$id.'\'';

		$t = mysql_query("UPDATE users SET password = $password WHERE id = $id");

		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES ($id, 'Изменение пароля', $time)");

		if($q && $t){
			echo "1";
			return;
		};
		echo "Ошибка";
	};

?>