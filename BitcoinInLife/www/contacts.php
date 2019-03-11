<?php
	// header("Content-type: text/html; charset=utf-8");
	include('php/model.php');


	$bul = 1;
	if(!isset($_COOKIE['login'])){
		$bul = 0;
	} else {

		// if(isset($_COOKIE['login']) && ($_COOKIE['login'] == 'admin'))
		// 	header('Location: admin.php');

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
	};
?>

<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/contacts.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/contacts.js"></script>
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
					<a href="contacts.php" style="text-decoration: underline;">Контакты</a>
				</li>
			</ul>

			<?
				if(isset($_COOKIE['login'])){
					$link = ($_COOKIE['login'] == 'admin') ? "admin.php" : "account.php";
			?>
					<div id="div_account">
						<a href=<? echo $link; ?> id="account"><? echo $_COOKIE['login']; ?></a>
					</div>
			<? } else { ?>
					<div id="autorization">
						<a href="register.php">Регистрация</a> /
						<a href="autorization.php">Вход</a>
					</div>
			<? 	}; ?>
		</div>

		
		<div id="content">
			<h2 align="center" style="margin-top: 20px; font-size: 34px;">Контакты</h2>

			<p id="info">
				Вы можете связаться с нами любым удобным способом. <br>
				email: bitcoininlife@yandex.ru <br>
				<!-- skype:  -->
				<br><br>
				Либо заполните форму ниже
			</p>


			<?
				if($bul){
			?>
					<div id="fio">
						<? echo $_SESSION['surname'].' '.$_SESSION['name'].' '.$_SESSION['patronymic']; ?>
					</div>

					<div id="login">
						<? echo $_COOKIE['login']; ?>
					</div>

					<div id="skype">
						<? echo $_SESSION['skype']; ?>
					</div>

					<input type="text" id="theme" placeholder="Тема">

					<textarea id="text" placeholder="Сообщение"></textarea>

					<div id="mail">
						Отправить
					</div>
			<? } else { ?>
					<p id="info2">
						Для отправки формы необходимо <a href="autorization.php" id="link_autorization">авторизироваться</a>
					</p>
			<? }; ?>

		</div>

	
		<div style="height: 120px;"></div>
		<div id="footer">
			<p>
				&copy; Bitcoin in Life. Все права защищены.
			</p>
		</div>
	</div>

</body>
</html>