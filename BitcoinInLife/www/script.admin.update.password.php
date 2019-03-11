<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'update'){

		$id = '\''.$_POST['uid'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$time = '\''.$_POST['time'].'\'';

		$q = mysql_query("UPDATE users SET password = $password WHERE id = $id");

		$login = '\''.get_login($id).'\'';

		$m = mysql_query("INSERT INTO history (uid, action, date) VALUES (1, \"Изменение пароля у пользователя $login\" , $time)");

		if($q){
			echo "1";
			return;
		};
		echo "Ошибка";
	};

	function get_login($id){
		$login = '(Пусто)';
		$t = mysql_query("SELECT * FROM users WHERE id = $id");
		if ($t != NULL)
			while($row = mysql_fetch_assoc($t)){
				$login = $row['login'];
			};
		return $login;
	};

?>