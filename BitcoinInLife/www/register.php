<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">

	<script src="js/jquery-1.11.0.js"></script>
	<script src="js/register.js"></script>
	
	<script type="text/javascript">
		window.parent = "<? echo $_GET['link']; ?>";
		// alert(window.parent);
	</script>
</head>
<body>

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
			<a href="register.php" style="text-decoration: underline;">Регистрация</a> /
			<a href="autorization.php">Вход</a>
		</div>
	</div>

	<div id="content">
		<h1 style="text-align: center;">Регистрация</h1>
		<table id="register_table" border="0">
			<tr>
				<td>
					<p class="input_text">Фамилия:</p>
				</td>
				<td>
					<input type="text" id="surname" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Имя:</p>
				</td>
				<td>
					<input type="text" id="name" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Отчество:</p>
				</td>
				<td>
					<input type="text" id="patronymic" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<?
				if(isset($_GET['link'])){
			?>
				<tr>
					<td>
						<p class="input_text">Пригласил:</p>
					</td>
					<td>
						<b style="font-size: 26px;">
							<? echo $_GET['link']; ?>
						</b>
					</td>
				</tr>
			<?
				};
			?>

			<tr>
				<td>
					<p class="input_text">Логин:</p>
					<p style="margin: 0; margin-left: 30px;">
						Будет использоваться при авторизации.<br>Только латинские буквы и цифры.
					</p>
				</td>
				<td>
					<input type="text" id="login" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Пароль:</p>
				</td>
				<td>
					<input type="password" id="password" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Повторите пароль:</p>
				</td>
				<td>
					<input type="password" id="password2" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">e-mail:</p>
				</td>
				<td>
					<input type="text" id="email" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Skype</p>
				</td>
				<td>
					<input type="text" id="skype" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Счёт Advanced Cash</p>
				</td>
				<td>
					<input type="text" id="advcash" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>

			<tr>
				<td>
					<p class="input_text">Защита от спама:</p>
					<p style="margin: 0; margin-left: 30px;">
						Введите символы с картинки
					</p>
				</td>
				<td>
					<img src="img/code.jpg" width="200px" style="float: left;">
					<p style="font-size: 22px; margin-top: 10px; margin-bottom: 12px; display: inline-block; margin-left: 20px;">
						Введите код:
					</p>
					<input type="text" id="code" class="input">
					<sup class="asterics">*</sup>
				</td>
			</tr>
		</table>

		<div id="reg_but">
			Зарегистрироваться
		</div>
	</div>

</body>
</html>