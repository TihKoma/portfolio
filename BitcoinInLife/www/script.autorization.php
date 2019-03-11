<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();

	if($_POST['action'] == 'autorization'){
		// $email = '"'.$_POST['email'].'"';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		// $password = '"'.md5($_POST['password']).'"';

		// echo $email.' '.$password;

		// $res = mysql_query("SELECT * FROM users WHERE email = \"$email\" AND password = \"$password\""); // if ($res != NULL) {
		// 		echo "ok";
		// 	while($row = mysql_fetch_assoc($res)){
		// 		echo $row['login'].'&';
		// 	};
		// };

		$q = mysql_query("SELECT * FROM users WHERE password = $password AND login = $login");
		if ($q != NULL) {
			while($row = mysql_fetch_assoc($q)) {
				// $homework = $row['homework'];
				$id = '\''.$row['id'].'\'';
				$login = $row['login'];
				$expire = time() + 3600 * 24 * 100;
				setcookie("id", $row['id'], $expire, "/");
				setcookie("login", $login, $expire, "/");
				// echo $_COOKIE['login'];
				// if (setcookie("login", $login, $expire)) echo "<h3>Cookies успешно установлены!</h3>";
				// session_start();
				$_SESSION['surname'] = $row['surname'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['patronymic'] = $row['patronymic'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['skype'] = $row['skype'];
				$_SESSION['advcash'] = $row['advcash'];
				$_SESSION['balance'] = $row['balance'];

				$z = mysql_query("SELECT * FROM tables WHERE id = $id");
				if($z != NULL){
					while($row = mysql_fetch_assoc($z)){
						$_SESSION['start'] = $row['start'];
						$_SESSION['main'] = $row['main'];
						$_SESSION['leader'] = $row['leader'];
						$_SESSION['vip'] = $row['vip'];
						$_SESSION['1nd'] = $row['1nd'];
						$_SESSION['2nd'] = $row['2nd'];
					};
				};

				if(!$z){
					echo "Ошибка доступа к данным о столах";
					return;
				};

				echo "1";
				return;
			};
		} else {
			echo "Данного пользователя не существует.";
		};
		echo "Данного пользователя не существует.";
	};

?>