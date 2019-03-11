<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/about.css">

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
					<a href="about.php" style="text-decoration: underline;">О нас</a>
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
			<div id="bg1">
				<div id="header1">
					<h2>
						Заработок, подарки и путешествия  вместе с нами
					</h2>
				</div>

				<div id="div_travel">
					<hr>

					<h3>
						Путешествия и санаторно-курортные лечения:
					</h3>

					<ul>
						<li id="airplane">Перелёт</li>
						<li id="eat">Питание</li>
						<li id="hotel">Проживание</li>
						<li id="heal">Лечение</li>
					</ul>

					<hr>				
				</div>

				<img src="img/travel2.png" id="img_travel">

				<div id="header2">
					<h2>
						Ищите  где  можно  быстро  заработать?  Тогда  вам  к  нам!!!
					</h2>
				</div>

				<table>
					<tr>
						<td>
							<img src="img/gadgets.jpg">
						</td>
						<td>
							<img src="img/sanatorium.jpg" width="340">
						</td>
						<td>
							<img src="img/travel.maps.jpg" width="340">
						</td>
					</tr>
				</table>
			</div>

			
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