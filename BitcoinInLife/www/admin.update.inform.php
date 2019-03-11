<?php
	// header("Content-type: text/html; charset=utf-8");
	include('php/model.php');



	if(!(isset($_COOKIE['login'])) || ($_COOKIE['login'] != 'admin'))
		header('Location: ../error.html');


	session_start();
	$login = '\''.$_COOKIE['login'].'\'';
	$id = '\''.$_COOKIE['id'].'\'';

	startup();

	if(!isset($_GET['uid']) || $_GET['uid'] == '')
		header('Location: ../error.html');

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
	<link rel="stylesheet" type="text/css" href="css/update.inform.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/admin.update.inform.js"></script>
	<script src="js/clipboard.min.js"></script>
</head>
<body>

	<script type="text/javascript">
		window.uid = <? echo $_GET['uid']; ?>
	</script>

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
				<a href="account.php" id="account"><? echo $_COOKIE['login']; ?></a>
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
			<a href="user.page.php?uid=<? echo $_GET['uid']; ?>" id="back">Назад</a>

			<h2 align="center" style="font-size: 28px;">Изменение информации об аккаунте</h2>

			<table id="table_user" border="0">
				<tr>
					<td>
						Логин
					</td>
					<td>
						<input type="text" id="login" class="input_inform" value="<? echo $login; ?>">
					</td>
				</tr>

				<tr>
					<td>
						Фамилия
					</td>
					<td>
						<input type="text" id="surname" class="input_inform" value="<? echo $surname; ?>">
					</td>
				</tr>

				<tr>
					<td>
						Имя
					</td>
					<td>
						<input type="text" id="name" class="input_inform" value="<? echo $name; ?>">
					</td>
				</tr>

				<tr>
					<td>
						Отчество
					</td>
					<td>
						<input type="text" id="patronymic" class="input_inform" value="<? echo $patronymic; ?>">
					</td>
				</tr>
					<tr>
					<td>
						email
					</td>
					<td>
						<input type="text" id="email" class="input_inform" value="<? echo $email; ?>">
					</td>
				</tr>

				<tr>
					<td>
						skype
					</td>
					<td>
						<input type="text" id="skype" class="input_inform" value="<? echo $skype; ?>">
					</td>
				</tr>

				<tr>
					<td>
						AdvCash
					</td>
					<td>
						<input type="text" id="advcash" class="input_inform" value="<? echo $advcash; ?>">
					</td>
				</tr>
			</table>

			<div id="save">
				Сохранить
			</div>
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