<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/autorization.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/register.css"> -->

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/autorization.js"></script>
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

			<div id="autorization">
				<a href="register.php">Регистрация</a> /
				<a href="autorization.php" style="text-decoration: underline;">Вход</a>
			</div>
		</div>


		<div id="content">
			<h2 align="center" style="font-size: 28px; margin-top: 16px;">Авторизация</h2>
			<input type="text" placeholder="Введите логин" class="inputs" id="login"> <br>
			<input type="password" placeholder="Введите пароль" class="inputs" id="password">

			<div id="entrance">
				Войти
			</div>
		</div>

		<div style="height: 100px;"></div>

		<div id="footer">
			<p>
				&copy; Bitcoin in Life. Все права защищены.
			</p>
		</div>
	</div>

</body>
</html>