<?php
	// header("Content-type: text/html; charset=utf-8");

	session_start();

	$expire = time() - 3600;

	// $res = 0;
	sleep(0.5);
	setcookie("login", "", $expire, "/");
	// 	$res = 1;

	setcookie("id", "", $expire, "/");
	// if(isset($_GET['cnt']))
	// 	$flag = 1;
	// else
	// 	$flag = 0;
	// 	$res += 1;

	// setcookie('login', '', $expire, '/');
	// setcookie('id', '', $expire, '/');

	// header("Refresh:3");

	// echo $_COOKIE['login'];

	unset($_COOKIE['login']);
	unset($_COOKIE['id']);

	unset($_SESSION['surname']);
	unset($_SESSION['name']);
	unset($_SESSION['patronymic']);
	unset($_SESSION['email']);
	unset($_SESSION['balance']);

	sleep(0.5);
	if(isset($_GET['repeat']))
		header('Location: index.php');
	else
		header('Location: exit.php?repeat=1');


	// if(setcookie("login", "0", time()-1, "/"))
	// 	$res = 1;

	// if(setcookie("id", "0", time()-1, "/"))
	// 	$res += 1;

	// unset($_COOKIE['login']);
	// unset($_COOKIE['id']);

	// unset($_SESSION['surname']);
	// unset($_SESSION['name']);
	// unset($_SESSION['patronymic']);
	// unset($_SESSION['email']);
	// unset($_SESSION['balance']);


	// if(setcookie("login", "0", time()-1, "/"))
	// 	$res = 1;

	// if(setcookie("id", "0", time()-1, "/"))
	// 	$res += 1;

	// unset($_COOKIE['login']);
	// unset($_COOKIE['id']);

	// unset($_SESSION['surname']);
	// unset($_SESSION['name']);
	// unset($_SESSION['patronymic']);
	// unset($_SESSION['email']);
	// unset($_SESSION['balance']);



	// header("Refresh:1");


	// session_destroy();
	// header('Location: exit.php');


	// header('Location: index.html');
	// if($res == 2)
		// echo "удалены.";

?>