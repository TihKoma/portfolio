<?php


	class user {
		function getFormLogin() {
			ob_start();
	 		include "/view/v.login.php";
	 		return ob_get_clean();
		}

		function login($_login, $_password) {
			$login = '\''.$_login.'\'';
			$password = '\''.md5($_password).'\'';

			$q = mysql_query("SELECT * FROM users WHERE login = $login AND password = $password");

			if (mysql_num_rows($q)>0){
				$expire = time() + 3600 * 24 * 100;
				setcookie("login", $_login, $expire);
				header('Location: index.php');
			};
		}

		function logout() {
			$expire = time() - 3600;
			setcookie("login", "", $expire);
			unset($_COOKIE['login']);
			session_destroy();

			header('Location: index.php');
		}

	};


?>