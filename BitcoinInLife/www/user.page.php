<?php
	// header("Content-type: text/html; charset=utf-8");
	include('php/model.php');



	if(!(isset($_COOKIE['login'])) || ($_COOKIE['login'] != 'admin'))
		header('Location: ../error.html');

	// if(isset($_COOKIE['login']) && ($_COOKIE['login'] == 'admin'))
	// 	header('Location: admin.php');

	session_start();
	$login = '\''.$_COOKIE['login'].'\'';
	$id = '\''.$_COOKIE['id'].'\'';

	startup();

	$uid = '\''.$_GET['uid'].'\'';
	$q = mysql_query("SELECT * FROM users WHERE id = $uid");
	if ($q != NULL) {
		while($row = mysql_fetch_assoc($q)){
			$login = $row['login'];
			$surname = $row['surname'];
			$name = $row['name'];
			$patronymic = $row['patronymic'];
			$email = $row['email'];
			$skype = $row['skype'];
			$advcash = $row['advcash'];
			$balance = $row['balance'];
		};
	};
?>

<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
	<link rel="stylesheet" type="text/css" href="css/user.page.css">

	<script src="js/jquery-1.11.0.js"></script>
</head>
<body>

	<div id="page">
		<div id="header">
			<div id="logo">
				<a href="index.php">
					<img src="img/logo.png" width="400px">
				</a>
				<div id="text_realty">
					Недвижимость
				</div>
			</div>

			<ul id="main_menu">
				<li>
					<a href="index.php">Главная</a>
				</li>
				<li>
					<a href="#">О нас</a>
				</li>
				<li>
					<a href="#">Документы</a>
				</li>
				<li>
					<a href="#">Новости</a>
				</li>
				<li>
					<a href="#">Контакты</a>
				</li>
			</ul>

			<div id="div_account">
				<a href="admin.php" id="account" style="text-decoration: underline;"><? echo $_COOKIE['login']; ?></a>
			</div>
		</div>

		<div id="left_side_bar">
			<h2 align="center">Аккаунт</h2>
			<div id="user">
				Логин: <b><? echo $_COOKIE['login']; ?></b> <br>
			</div>
			<a href="exit.php" id="exit">Выйти</a>

			<a href="history.php" class="admin_menu" style="margin-top: 50px;">История операций</a>
			<a href="list.php" class="admin_menu" style="padding-left: 4px;">Список пользователей</a>
			<a href="admin.output.money.php" class="admin_menu" style="padding-left: 14px;">
				Запросы на вывод <qwe style="font-family: times new roman;">$</qwe>
			</a>
			<a href="admin.input.money.php" class="admin_menu" style="padding-left: 14px;">
				Запросы на ввод <qwe style="font-family: times new roman;">$</qwe>
			</a>
		</div>


		<div id="content">
			<h2 align="center" style="font-size: 28px;">Страница пользователя</h2>

			<table id="table_user" border="0">
				<tr>
					<td>
						Логин
					</td>
					<td>
						<? echo $login; ?>
					</td>
				</tr>

				<tr>
					<td>
						Фамилия
					</td>
					<td>
						<? echo $surname; ?>
					</td>
				</tr>

				<tr>
					<td>
						Имя
					</td>
					<td>
						<? echo $name; ?>
					</td>
				</tr>

				<tr>
					<td>
						Отчество
					</td>
					<td>
						<? echo $patronymic; ?>
					</td>
				</tr>

				<tr>
					<td>
						email
					</td>
					<td>
						<? echo $email; ?>
					</td>
				</tr>

				<tr>
					<td>
						skype
					</td>
					<td>
						<? echo $skype; ?>
					</td>
				</tr>

				<tr>
					<td>
						Advanced Cash
					</td>
					<td>
						<? echo $advcash; ?>
					</td>
				</tr>

				<tr>
					<td>
						Баланс
					</td>
					<td>
						<qwe style="font-family: times new roman;">$</qwe><? echo $balance; ?>
					</td>
				</tr>
			</table>

			<a id="update" href="admin.update.inform.php?uid=<? echo $_GET['uid']; ?>">Изменить информацию</a>
			<a id="update_password" href="admin.update.password.php?uid=<? echo $_GET['uid']; ?>">Изменить пароль</a>
		</div>

		<div style="height: 900px;"></div>

		<div id="footer">
			<p>
				&copy; Bitcoin in Life. Все права защищены.
			</p>
		</div>
	</div>
</body>
</html>