<?php
	header("Content-type: text/html; charset=utf-8");
	include('model.php');
	startup();

	if (isset($_POST['action']) && ($_POST['action'] == "get_names")) {
		$res = mysql_query("SELECT * FROM about_school");
		// $name = array(); 
		while($row = mysql_fetch_assoc($res)) {
			$name .= $row['full_name'].'%';
			$id .= $row['id'].'?';
		};
		$name = substr($name, 0, -1); //удаление лишнего '%'
		$id = substr($id, 0, -1); //удаление лишнего '?'
		$res = $name.'@'.$id;
		echo $res;

	};

	if (isset($_POST['action']) && ($_POST['action'] == 'log_in')) {
		// echo $_POST['school'].$_POST['who'].$_POST['login'].$_POST['password'];
		$school = '\''.$_POST['school'].'\'';
		// $who = $_POST['who'];
		if ($_POST['who'] == 'Преподаватель') {
			$who = 'teachers';
		} elseif ($_POST['who'] == 'Ученик') {
			$who = 'students';
		} elseif ($_POST['who'] == 'Родитель') {
			$who = 'parents';
		} elseif ($_POST['who'] == 'Администратор') {
			$who = 'administrators';
		};
		$login = "\"".$_POST['login']."\"";
		$password = $_POST['password'];
	//Получаем id школы
		$a = mysql_query("SELECT id FROM about_school WHERE full_name = $school");
		while($row = mysql_fetch_assoc($a)) {
			$id = $row['id'];
		};
		$id_school = $id;
		$id = '\''.$id.'\'';
	//Получаем пароль из БД по логину и id школы
		$table = $who;
		$q = mysql_query("SELECT * FROM $table WHERE id_school = $id_school AND login = $login");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$id_user = $row['id'];
				$fio = $row['FIO'];
				$pass = $row['password'];
			};
		};
		// echo "$id_user".' '."$fio".' '."$password";

		if ($who == 'teachers') {
			$priv = 1;
			$user = 'преподавателя';
		} elseif ($who == 'students') {
			$priv = 2;
			$user = 'ученика';
		} elseif ($who == 'parents') {
			$priv = 3;
			$user = 'родителя';
		} elseif ($who == 'administrators') {
			$priv = 4;
			$user = 'администратора';
		};

		if ($pass == null) {
			$err = 'В данной школе '.$user.' с логином '.$login.' не существует';
			echo $err;
			return;
		};
		if (md5($password) != $pass) return; //"err_log_in";
			// echo "Вход выполен";
		$expire = time() + 3600 * 24 * 100;
		setcookie('login', $_POST['login'], $expire);
		setcookie('password', md5($password), $expire);
		setcookie('priv', $priv, $expire);
		setcookie('id_school', $id_school, $expire);
		session_start();
		$_SESSION['name'] = $fio;
		$_SESSION['id_school'] = $id_school;
		// echo $_SESSION['name'];
		if ($priv == 1) {
			// header("Location: index_teacher.php");
			echo "index_teacher.html";
			// exit;
		} elseif ($priv == 2) {
			$_SESSION['id_student'] = $id_user;
			echo "index_student.html";
		} elseif ($priv == 3) {
			echo "index_parent.html";
		} elseif ($priv == 4) {
			echo "index_admin.html";
		};
			// echo $_COOKIE['login'];
			// echo $_COOKIE['password'];
			// echo $_COOKIE['priv'];

		// echo $login;
		// echo $fio;
		// $fio = substr($id, 0, -1);
	}

?>