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
	<link rel="stylesheet" type="text/css" href="css/update.password.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/admin.update.password.js"></script>
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
		
			<h2 align="center" style="font-size: 28px;">Изменение пароля</h2>

			<table id="table_update" border="0" style="height: 160px;">
				<tr>
					<td>
						У пользователя
					</td>
					<td>
						<b style="font-family: calibri; font-size: 26px;">
							<? echo get_login($uid); ?>
						</b>
					</td>
				</tr>

				<tr>
					<td>
						Новый пароль
					</td>
					<td>
						<input type="password" id="password" class="input_pass">
					</td>
				</tr>

				<tr>
					<td>
						Повторите пароль
					</td>
					<td>
						<input type="password" id="password2" class="input_pass">
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