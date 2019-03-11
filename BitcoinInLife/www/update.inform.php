<?php
	// header("Content-type: text/html; charset=utf-8");
	include('php/model.php');



	if(!(isset($_COOKIE['login'])))
		header('Location: ../error.html');


	session_start();
	$login = '\''.$_COOKIE['login'].'\'';
	$id = '\''.$_COOKIE['id'].'\'';

	startup();

	// Если сессия закрыта, возобновляем данные о пользователе.
	if(!isset($_SESSION['email'])){
		$q = mysql_query("SELECT * FROM users WHERE login = $login");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)){
				$_SESSION['surname'] = $row['surname'];
				$_SESSION['name'] = $row['name'];
				$_SESSION['patronymic'] = $row['patronymic'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['skype'] = $row['skype'];
				$_SESSION['advcash'] = $row['advcash'];
				$_SESSION['balance'] = $row['balance'];
				// echo "1";
			};
		} else
			header('Location: ../error.html');

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
	} else {
		$q = mysql_query("SELECT * FROM users WHERE login = $login");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)){
				$_SESSION['balance'] = $row['balance'];
			};
		}
	};
?>

<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<link rel="stylesheet" type="text/css" href="css/update.inform.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/update.inform.js"></script>
	<script src="js/clipboard.min.js"></script>
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
				<a href="account.php" id="account"><? echo $_COOKIE['login']; ?></a>
			</div>
		</div>

		<div id="left_side_bar">
			<h2 align="center" id="text_account">Аккаунт</h2>
			<div id="user">
				Логин: <b><? echo $_COOKIE['login']; ?></b> <br>
				Баланс: <b><qwe style="font-family: times new roman;">$</qwe><? echo $_SESSION['balance']; ?></b>
			</div>
			<a href="input.money.php" id="replenish">Пополнить баланс</a> <br>
			<a href="exit.php" id="exit">Выйти</a>

			<h3 align="center">Личная ссылка</h3>


			<div id="link" data-clipboard-target="#link"><? echo "bitcoininlife.ru?link=".$_COOKIE['login']; ?></div>
				<!-- bitcoininlife.ru?link=qwertyuiopas2017 -->
			<script>
				new Clipboard('#link');
			</script>

			<p id="text_click">
				Нажмите для копирования <br>
				<!-- <qwe style="font-size: 12px;">
					(Будет доступна после активации стола)
				</qwe> -->
			</p>

			<a href="gold.ring.10.php" class="tables" id="link_gold_ring">
				Gold Ring
			</a>

			<?
				$txt_start = '';
				$txt_main = '';
				$txt_leader = '';
				$txt_vip = '';
				$txt_1nd = '';
				$txt_2nd = '';

				// if($_SESSION['start'] == 1)
				$txt_start = "href=\"start.php\" style=\"cursor: pointer;\"";

				if($_SESSION['main'] == 1)
					$txt_main = "href=\"main.php\" style=\"cursor: pointer;\"";
				if($_SESSION['leader'] == 1)
					$txt_leader = "href=\"leader.php\" style=\"cursor: pointer;\"";
				if($_SESSION['vip'] == 1)
					$txt_vip = "href=\"vip.php\" style=\"cursor: pointer;\"";
				if($_SESSION['1nd'] == 1)
					$txt_1nd = "href=\"1nd.php\" style=\"cursor: pointer;\"";
				if($_SESSION['2nd'] == 1)
					$txt_2nd = "href=\"2nd.php\" style=\"cursor: pointer;\"";
			?>
			<a <? echo $txt_start; ?> class="tables" id="link_start">
				Старт
			</a>
			<a <? echo $txt_main; ?> class="tables">
				Основной
			</a>
			<a <? echo $txt_leader; ?> class="tables">
				Лидерский
			</a>
			<a <? echo $txt_vip; ?> class="tables">
				ВИП
			</a>
			<a <? echo $txt_1nd; ?> class="tables">
				1ND
			</a>
			<a <? echo $txt_2nd; ?> class="tables">
				2ND
			</a>
		</div>

		<div id="content">
			<a href="account.php" id="back">Назад</a>

			<h2 align="center" style="font-size: 28px;">Изменение информации об аккаунте</h2>

			<table id="table_user" border="0">
				<tr>
					<td>
						Фамилия
					</td>
					<td>
						<input type="text" id="surname" class="input_inform" value="<? echo $_SESSION['surname']; ?>">
					</td>
				</tr>

				<tr>
					<td>
						Имя
					</td>
					<td>
						<input type="text" id="name" class="input_inform" value="<? echo $_SESSION['name']; ?>">
					</td>
				</tr>

				<tr>
					<td>
						Отчество
					</td>
					<td>
						<input type="text" id="patronymic" class="input_inform" value="<? echo $_SESSION['patronymic']; ?>">
					</td>
				</tr>
					<tr>
					<td>
						email
					</td>
					<td>
						<input type="text" id="email" class="input_inform" value="<? echo $_SESSION['email']; ?>">
					</td>
				</tr>

				<tr>
					<td>
						skype
					</td>
					<td>
						<input type="text" id="skype" class="input_inform" value="<? echo $_SESSION['skype']; ?>">
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