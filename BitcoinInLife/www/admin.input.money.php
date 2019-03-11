<?php
	// header("Content-type: text/html; charset=utf-8");
	include('php/model.php');



	if(!(isset($_COOKIE['login'])) || ($_COOKIE['login'] != 'admin'))
		header('Location: ../error.html');

	session_start();
	$login = '\''.$_COOKIE['login'].'\'';
	$id = '\''.$_COOKIE['id'].'\'';

	startup();

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

<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<link rel="stylesheet" type="text/css" href="css/admin.css">
	<link rel="stylesheet" type="text/css" href="css/admin.input.money.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/admin.input.money.js"></script>
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
					<a href="about.php">О нас</a>
				</li>
				<li>
					<a href="documents.php">Документы</a>
				</li>
				<li>
					<a href="#">Новости</a>
				</li>
				<li>
					<a href="contacts.php">Контакты</a>
				</li>
			</ul>

			<div id="div_account">
				<a href="admin.php" id="account"><? echo $_COOKIE['login']; ?></a>
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
			<a href="admin.input.money.php" class="admin_menu" style="padding-left: 14px; background-image: url('../img/bg.jpg');">
				Запросы на ввод <qwe style="font-family: times new roman;">$</qwe>
			</a>
		</div>

		<div id="content">
			<div id="delete">Удалить</div>

			<h2 align="center" style="font-size: 28px;">
				Запросы на ввод <qwe style="font-family: times new roman;">$</qwe>
			</h2>

			<table id="table_input" border="1">
				<tr>
					<td style="width: 21px;"></td>
					<td class="headers" style="width: 180px;">Пользователь</td>
					<td class="headers" style="width: 120px;">Платёжная система</td>
					<td class="headers" style="">Номер карты</td>
					<td class="headers" style="width: 70px;">Сумма, <qwe style="font-family: times new roman;">$</qwe></td>
					<td class="headers" style="">Дата/Время</td>
					<td style="width: 186px;"></td>
				</tr>

				<?
					$q = mysql_query("SELECT * FROM input_money WHERE status = 0");
					if ($q != NULL) {
						while($row = mysql_fetch_assoc($q)){
							$login = get_login($row['uid']);
				?>

				<tr>
					<td>
						<input type="checkbox" class="request" id="request_<? echo $row['id']; ?>">
					</td>
					<td>
						<a href="<? echo "user.page.php?uid=".$row['uid']; ?>" class="user_login">
							<? echo $login; ?>
						</a>
					</td>
					<td class="payment">
						<? echo $row['payment']; ?>
					</td>
					<td class="card">
						<? echo $row['card']; ?>
					</td>
					<td class="count">
						<? echo $row['count']; ?>
					</td>
					<td class="time">
						<? echo $row['time']; ?>
					</td>
					<td>
						<div id="<? echo $row['id']; ?>" class="confirm" uid="<? echo $row['uid']; ?>" count="<? echo $row['count']; ?>">
							Подтвердить
						</div>
					</td>
				</tr>

				<?
						} };
				?>

			</table>
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