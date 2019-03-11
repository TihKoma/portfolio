<?php
	include('php/model.php');

	

	// if(isset($_COOKIE['login']) && ($_COOKIE['login'] == 'admin'))
	// 	header('Location: admin.php');

	// session_start();
	// $login = '\''.$_COOKIE['login'].'\'';
	// $id = '\''.$_COOKIE['id'].'\'';

	// startup();

	if(isset($_GET['link'])){
		$link = "Location: https://bitcoininlife.ru/register.php?link=".$_GET['link'];
		header($link);
	};

?>

<!DOCTYPE html>
<html>
<head>
	<title>Bitcoin in Life</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">

	<link rel="stylesheet" href="slider/css/slider.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="slider/src/slider.js"></script>
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
					<a href="index.php" style="text-decoration: underline;">Главная</a>
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

	<!--========================================== Слайдер =======================================-->
		<div class="slider-container">
			<div class="slider" id="slider">
			    <div class="slider__item">
			    	<img src="img/6.jpg" alt="">
			    	<span class="slider__caption"> 
			    	</span>
			    </div>
			    <div class="slider__item">
			    	<img src="img/2.jpg" alt="">
			    	<!-- <span class="slider__caption">2 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa, facilis.</span> -->
			    </div>
			    <div class="slider__item">
			    	<img src="img/3.jpg" alt="">
			    	<!-- <span class="slider__caption">3 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit, culpa!</span> -->
			    </div>
		 	</div>
		    <!-- <a href="" class="slider__switch slider__switch--prev" data-ikslider-dir="prev"><span></span></a> -->
		    <!-- <a href="" class="slider__switch slider__switch--next" data-ikslider-dir="next"><span></span></a> -->
		    <div class="slider__switch slider__switch--prev" data-ikslider-dir="prev">
		      <span><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 20 20"><path d="M13.89 17.418c.27.272.27.71 0 .98s-.7.27-.968 0l-7.83-7.91c-.268-.27-.268-.706 0-.978l7.83-7.908c.268-.27.7-.27.97 0s.267.71 0 .98L6.75 10l7.14 7.418z"/></svg></span>
		    </div>
		    <div class="slider__switch slider__switch--next" data-ikslider-dir="next">
		      <span><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 20 20"><path d="M13.25 10L6.11 2.58c-.27-.27-.27-.707 0-.98.267-.27.7-.27.968 0l7.83 7.91c.268.27.268.708 0 .978l-7.83 7.908c-.268.27-.7.27-.97 0s-.267-.707 0-.98L13.25 10z"/></svg></span>
		    </div>
		</div>

		<script>
			$(".slider-container").ikSlider();
		</script>
	<!-- =========================================================================================-->

	<!-- ========================================= Информационно-рекламный блок=================== -->
		<div id="advert">
			<h1>
				Ваша мечта здесь!
			</h1>
			<p>
				Мы даем уникальную возможность
				изменить свою жизнь к лучшему! 
				Компания предлагает уникальную 
				возможность увеличить свои доходы.
				Зарабатывая, Вы в короткие сроки 
				осуществите свои мечты!
			</p>
			<hr>
		</div>
	<!-- =========================================================================================-->

	<!-- ========================================= Цитата Франклина=============================== -->
		<div id="quote">
			<h1>
				“Помните, что деньги
			</h1>
			<h1 style="margin-left: 120px; margin-top: -20px;">
				обладают способностью размножаться...”
			</h1>
			<h1 id="franclin" style="font-size: 32px;">
				Бенджамин Франклин
			</h1>
		</div>
	<!-- =========================================================================================-->

		<h1 style="text-align: center; margin-top: 300px; font-size: 35px;">
			Зарабатываем Биткоин и покупаем недвижимость <br> по всему миру!
		</h1>

	<!-- ========================================= Описание сервиса =============================== -->
		<div id="description">
			<img src="img/home.png" id="img_home">

			<div id="description_text">
				<h1>
					Bitcoin in Life!
				</h1>
				<p>
					Bitcoininlife – это не просто сайт,
					где можно заработать
					хорошие деньги.
					Но также и купить недвижимость
					с помощью администраторов
					компании, через застройщиков
					по всему миру.
				</p>
				<ul>
					<li>Никаких сплитов и заморозок денег</li>
					<li>Все деньги хранятся на вашем личном кошельке, 
    					доступ к которому имеете только вы
    				</li>
					<li>Шикарный маркетинг</li>
					<li>Обучение работе в интернете</li>
				</ul>
			</div>
		</div> <br>
	<!-- =========================================================================================-->

		<h1 style="text-align: center; margin-top: 80px; font-size: 35px;">
			Нам можно доверять
		</h1> <br><br>

	<!-- ========================================= Преимущества=== =============================== -->
		<table id="advantages" border="0">
			<tr style="height: 80px;">
				<td style="width: 27%;">
					<img src="img/simbol_home.png" width="80px;" style="margin-left: 20px; display: block; float: left;">
					<h2>
						Недвижимость <br> от поставщика
					</h2>
				</td>
				<td style="width: 27%;">
					<img src="img/simbol_home.png" width="80px;" style="margin-left: 20px; display: block; float: left;">
					<h2>
						Интернет <br>недвижимость
					</h2>
				</td>
				<td style="width: 27%;">
					<img src="img/simbol_home.png" width="80px;" style="margin-left: 20px; display: block; float: left;">
					<h2>
						Недвижимость <br>по всему миру
					</h2>
				</td>
			</tr>

			<tr>
				<td>
					<p>
						Недвижимость за рубежом, каждый может купить... 
						Работаем на прямую с 
						застройщиками.
						Проект "BITCOIN IN LIFE" - это отлаженный бизнес - инструмент с серьезными обучающими программами. Используя его, Вы и Ваши партнёры сможете индивидуально вести свой бизнес, управляя им на всех этапах. Вы и только Вы являетесь гарантом успеха, а мы гарантируем безупречную работу программ, службы поддержки и администраторов проекта.
					</p>
				</td>
				<td style="padding-left: 20px;">
					<p>
						Наш сайт предоставляет возможность заработать всем!
						Купить дом или квартиру в …
						Вероятно, вы об этом пока и не задумываетесь. Но кто знает, может это именно та страна, жизнь в которой максимально соответствует вашим желаниям и возможностям.
					</p>
				</td>
				<td style="padding-left: 20px;">
					<p>
						Прежде чем перебраться в страну на ПМЖ, совершенно необходимо хорошенько изучить особенности жизни в ней и отдельное внимание уделить ценам.Ведь отдыхать пару недель в году и жить постоянно – совершенно разные вещи. Для начала советуем сделать пробную поездку на месяц-другой. 
					</p>
				</td>
			</tr>
		</table>
	<!-- =========================================================================================-->
		
		<div style="height: 80px;"></div>
		<div id="footer">
			<p>
				&copy; Bitcoin in Life. Все права защищены.
			</p>
		</div>
	</div>

</body>
</html>