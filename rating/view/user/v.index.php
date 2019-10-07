<?php
	require_once 'id2/config.php';
	require_once 'id2/id2class.php';
	$id2 = new Id2action($appid, $pass);
	$reglink = $id2->getRegistrationLink($callbackurl);

	
	$profile = $id2->GetProfile($token);
	$id2uid = $profile['Data']['Id'];
	$fname = $profile['Data']['FirstName'];
	$sname = $profile['Data']['LastName'];
	$uemail = $profile['Data']['Email'];
	
	$uname = "$FirstName $LastName";
	
	$utcode = md5($uid);


	$str = (isset($_COOKIE['uid']) && $_COOKIE['uid']) ? "Продолжить участие" : "Принять участие";

	if (isset($_COOKIE['bitrixId']))
		$linkProfile = "<a href=\"http://".HOST_NAME."/profile/\"><div>$str</div></a>";
	else
		$linkProfile = "<button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#myModal\">$str</button>";


	if (isset($_COOKIE['bitrixId']) && $_COOKIE['bitrixId'] )
		if (isset($_COOKIE['uid']) && $_COOKIE['uid'])
			$linkSeeMyPlace = "<a href=\"#myPlace\">Посмотреть мое место в Рейтинге</a>";
		else
			$linkSeeMyPlace = "<a href=\"/profile\">Посмотреть мое место в Рейтинге</a>";
	else
		$linkSeeMyPlace = "<a data-toggle=\"modal\" data-target=\"#myModal\">Посмотреть мое место в Рейтинге</a>";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Главная</title>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="view/user/css/bootstrap.css">
	<link rel="stylesheet" href="view/user/css/bootstrap-glyphicons.css">
	<link rel="stylesheet" href="view/user/css/index.css">
	<!-- <link rel="stylesheet" href="css/profile.css"> -->
	<!-- <link href="css/style.css" rel="stylesheet"> -->
	<!-- <link href="css/font-awesome.css" rel="stylesheet"> -->
	<?php require_once 'id2/id2_script.php'; ?>



	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script >
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->  
</head>
<body>

	<div class="container-fluid row" id="header1">
		<div class="col-lg-5 col-md-4 col-sm-2 col-xs-2">
			<p class="header1_tr" style="float: none;">
				<img src="view/user/img/action.pravo.svg" height="16px">
			</p>
		</div>

		<div class="col-lg-7 col-md-8 col-sm-10 col-xs-10">
			<div class="row">
				<div class="col-lg-0 col-md-0 col-sm-0"></div>

				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

					<div id="panelcontainer"></div>


					<!-- <a href="#" class="header1_tr hidden-xs">
						О системе
					</a>

					<a href="#" class="header1_tr hidden-xs">
						Версия для коммерческих организаций
					</a> -->

				</div>

				<!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
					
				</div> -->
			</div>
		</div>
	</div>

			
	<div class="container-fluid row" id="header2">
		<!-- <div class="col-lg-2 col-md-0 col-sm-0 col-xs-0"></div> -->

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

			<div class="navbar navbar-default navbar_rating">
				<div class="container">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-12">
							<div class="navbar-header row" style="min-width: 430px;">
								<div class="col-xs-10" style="min-width: 329px;">
									<a class="navbar-brand" href="http://<?php echo HOST_NAME; ?>" id="link_logo">
										<img src="view/user/img/logo.png">
									</a>
								</div>

								<div class="col-xs-2">
									<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
										<span class="sr-only">Открыть навигацию</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
							</div>
						</div>

						<div class="col-lg-7 col-md-7 col-sm-0">
							<div class="collapse navbar-collapse" id="responsive-menu">
								<ul class="nav navbar-nav header2_navbar">
									<li><a href="#">ГЛАВНАЯ</a></li>
									<li><a href="#">РЕЙТИНГ</a></li>
									<li><a href="#">КАК ИСПОЛЬЗОВАТЬ</a></li>
									<li><a href="#">КОНТАКТЫ</a></li>
									<li>
										<?php
											if (isset($_COOKIE['bitrixId']) && $_COOKIE['bitrixId'])
												echo "<a id=\"link_logout\" href=\"http://".HOST_NAME."/server/logout\">Выйти</a>";
										?>
									</li>

									<!-- <li>
										<a href="#" id="link_account">
											<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
											ЛИЧНЫЙ КАБИНЕТ
										</a>
									</li> -->
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="col-lg-0 col-md-0 col-sm-0 col-xs-0"></div> -->
	</div>


	<div class="container-fluid row" id="index_purple_block">
		<!-- <div class="col-lg-2 col-md-1 col-sm-0 col-xs-0"></div> -->

		<div class="container">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div id="container_counter">
					<span>
						ЮРИСТОВ В РЕЙТИНГЕ
					</span>

					<div>
						<p>
							<?php echo count($ratingList); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div id="container_tagline">
					<p>
						Слоган рейтинга - быстрый старт рядом счетчик, сколько юристов уже в деле
					</p>

					<?php echo $linkProfile; ?>
				</div>
			</div>
		</div>

		<!-- <div class="col-lg-2 col-md-1 col-sm-0 col-xs-0"></div> -->
	</div>


	<div class="container" id="importance">
		<!-- <div class="col-lg-2 col-md-2 col-sm-0 col-xs-0"></div> -->

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h1>
					ПОЧЕМУ ВАЖНО УЧАСТВОВАТЬ
				</h1>

				<p>
					Здесь должно быть описание почему так важно участвовать в этом проекте рейнтинга юристов. текст текст текст текст текст текст текст текст текст текст текст текст текст
				</p>
			</div>
		</div>

		<!-- <div class="col-lg-2 col-md-2 col-sm-0 col-xs-0"></div> -->
	</div>



<!-- ======================================================Слайдер======================================================== -->
	<div class="container">
		<!-- <div class="col-lg-2 col-md-2 col-sm-1 col-xs-0"></div> -->

	
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div id="slider" class="carousel slide" data-ride="carousel">
					<!-- Индикаторы карусели -->
					<ol class="carousel-indicators">
						<!-- Перейти к слайду №0 с помощью соответствующего атрибута с индексом data-slide-to="0" -->
						<li data-target="#slider" data-slide-to="0" class="active"></li>
						<!-- Перейти к слайду №1 с помощью соответствующего индекса data-slide-to="1" -->
						<li data-target="#slider" data-slide-to="1"></li>
						<!-- Перейти к слайду №1 с помощью соответствующего индекса data-slide-to="2" -->
						<li data-target="#slider" data-slide-to="2"></li>
					</ol>

					<!-- Слайды карусели -->
					<div class="carousel-inner">
						<!-- Слайд 1 -->
						<div class="item active">
							<div class="row slider_first_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<i>
										«Отзыв известного юриста, вернее локоничная цитата про то, как прекрасен рейнтинг журнала Юриста компании»
									</i>
								</div>

								<div class="col-lg-4 col-md-4 col-md-4 col-sm-4 hidden-sm hidden-xs">
									<img src="view/user/img/main.person.png">
								</div>
							</div>

							<div class="row slider_second_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="author">
									<b>АЛЕКСЕЙ КОСТОВАРОВ</b> <br>
									Советник практики разрешения споров <br>
									и банкротства АБ «Линия права»
								</div>

								<div class="col-lg-4 col-md-4"></div>
							</div>
						</div>
						<!-- Слайд 2 -->
						<div class="item">
							<div class="row slider_first_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<i>
										«Отзыв известного юриста, вернее локоничная цитата про то, как прекрасен рейнтинг журнала Юриста компании»
									</i>
								</div>

								<div class="col-lg-4 col-md-4 col-md-4 col-sm-4 hidden-sm hidden-xs">
									<img src="view/user/img/main.person.png">
								</div>
							</div>

							<div class="row slider_second_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="author">
									<b>АЛЕКСЕЙ КОСТОВАРОВ</b> <br>
									Советник практики разрешения споров <br>
									и банкротства АБ «Линия права»
								</div>

								<div class="col-lg-4 col-md-4"></div>
							</div>
						</div>
						<!-- Слайд 3 -->
						<div class="item">
							<div class="row slider_first_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<i>
										«Отзыв известного юриста, вернее локоничная цитата про то, как прекрасен рейнтинг журнала Юриста компании»
									</i>
								</div>

								<div class="col-lg-4 col-md-4 col-md-4 col-sm-4 hidden-sm hidden-xs">
									<img src="view/user/img/main.person.png">
								</div>
							</div>

							<div class="row slider_second_tr">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="author">
									<b>АЛЕКСЕЙ КОСТОВАРОВ</b> <br>
									Советник практики разрешения споров <br>
									и банкротства АБ «Линия права»
								</div>

								<div class="col-lg-4 col-md-4"></div>
							</div>
						</div>
					</div>

					<!-- Навигация карусели (следующий или предыдущий слайд) -->
					<!-- Кнопка, переход на предыдущий слайд с помощью атрибута data-slide="prev" -->
					<a class="left carousel-control" href="#slider" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<!-- Кнопка, переход на следующий слайд с помощью атрибута data-slide="next" -->
					<a class="right carousel-control" href="#slider" role="button" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>
				</div>
			</div>
		</div>
	

		<!-- <div class="col-lg-2 col-md-2 col-sm-1 col-xs-0"></div> -->
	</div>








	<div class="container-fluid row" style="padding: 0;">
		<div class="col-md-12" id="line"></div>
	</div>

	<div class="container-fluid">
		<!-- <div class="col-lg-0 col-md-0 col-sm-0 col-xs-0"></div> -->

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="container_group">
				<h1>
					Заголовок
				</h1>

				<p>
					Выберите интересующую вас группу по стажу работы:
				</p>



				<!-- <div class="container center-block "> -->
					<!-- <div class="col-lg-2 col-md-2"></div> -->
				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-block group">
						<div class="btn_group btn_experience" experience="0">
							Новичок
						</div>

						<div class="btn_group btn_experience" experience="1">
							Профи
						</div>

						<div class="btn_group btn_experience" experience="2">
							Гуру
						</div>
					</div>

				</div>


				<p>
					Выберите интересующую вас группу по отрасли работы:
				</p>



				<!-- <div class="container center-block "> -->
					<!-- <div class="col-lg-2 col-md-2"></div> -->
				<div class="row">

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 center-block group">
						<div class="btn_group btn_sector" sector="1">
							Судебные споры
						</div>

						<div class="btn_group btn_sector" sector="2">
							Договорная работа
						</div>

						<div class="btn_group btn_sector" sector="3">
							Интеллектуальная собственность
						</div>

						<div class="btn_group btn_sector" sector="4">
							Корпоративное сопровождение компании
						</div>

						<div class="btn_group btn_sector" sector="5">
							Налоговое право
						</div>

						<div class="btn_group btn_sector" sector="6">
							Недвижимость
						</div>

						<div class="btn_group btn_sector" sector="7">
							Трудовое право
						</div>

						<div class="btn_group btn_sector" sector="8">
							Банкротство
						</div>
					</div>

				</div>
					<!-- <div class="col-lg-2 col-md-2"></div> -->
				<!-- </div> -->




				<div class="row">
					<div class="col-md-12" id="div_my_position">
						<span id="link_my_position">
							<?php echo $linkSeeMyPlace; ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="col-lg-0 col-md-0 col-sm-0 col-xs-0"></div> -->
	</div>


	<div class="container" id="container_rating">
		<div class="row" style="min-width: 430px;">
			<div class="col-md-12">
				<div class="row" id="first_tr">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 first_tr_value">
						МЕСТО
					</div>

					<div class="col-lg-8 col-md-8 hidden-sm hidden-xs first_tr_value">
						УЧАСТНИК
					</div>
					<div class="col-lg-2 col-md-2 col-sm-10 col-xs-9 first_tr_value">
						БАЛЛЫ
					</div>
				</div>
			</div>
		</div>

		<?php
			$count = count($ratingList);
			for ($i = 0; $i < $count; $i++) {
				$isMyPlace = "";
				if ($_COOKIE['uid'] == $ratingList[$i]['id'])
					$isMyPlace = " id=\"myPlace\"";
		?>

				<div <?php echo $isMyPlace; ?> class="row hidden-sm hidden-xs" style="margin-top: 10px;">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="row rating_tr">
							<div class="col-md-2 rating_tr_value additional_info">
								<b>
									<?php echo $i+1; ?>
								</b>
							</div>

							<div class="col-md-8 rating_tr_value row">
								<div class="col-lg-2 col-md-3">
									<img src="/view/user/img_user/<?php echo $ratingList[$i]['img']; ?>">
								</div>

								<div class="col-lg-10 col-md-9 personal_value">
									<div class="row">
										<span>
											<?php echo $ratingList[$i]['surname']." ".$ratingList[$i]['name'] ?>
										</span>
									</div>

									<div class="row">
										<p>
											<?php echo $ratingList[$i]['position']; ?>
										</p>
									</div>
								</div>
							</div>

							<div class="col-md-2 rating_tr_value additional_info">
								<?php echo $ratingList[$i]['score']; ?>
							</div>
						</div>
					</div>
				</div>


				<div class="row hidden-lg hidden-md" style="margin-top: 10px; min-width: 430px;">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="row rating_tr">
							<div class="col-sm-2 col-xs-3 rating_tr_value">
								<b>
									<?php echo $i+1; ?>
								</b>
							</div>

							<div class="col-sm-10 col-xs-9 rating_tr_value">
								<?php echo $ratingList[$i]['score']; ?>
							</div>

							<div class="col-sm-12 col-xs-12 rating_tr_value row" style="border-top: 1px solid black;">
								<div class="col-sm-2 col-xs-3">
									<img src="/view/user/img_user/<?php echo $ratingList[$i]['img']; ?>">
								</div>

								<div class="col-sm-10 col-xs-9 personal_value">
									<div class="row">
										<span>
											<?php echo $ratingList[$i]['surname']." ".$ratingList[$i]['name'] ?>
										</span>
									</div>

									<div class="row">
										<p>
											<?php echo $ratingList[$i]['position']; ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php
			};
		?>

	</div>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Авторизация/регистрация</h4>
			</div>
			<div class="modal-body">
				Для участия в рейтинга вам необходимо <span class="id2-rx-user-informer-button id2-rx-noauth-informer-button">авторизоваться или зарегистрироваться</span>
			</div>

		</div>
	</div>
</div>




<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>


	<script type="text/javascript" src="view/user/js/jquery-3.4.1.js"></script>
	<script src="view/user/js/bootstrap.js"></script>
	<script type="text/javascript" src="view/user/js/index.js"></script>

	<?php
		if (isset($_GET['unreg']) && $_GET['unreg'] == 1 && (!isset($_COOKIE) || !$_COOKIE['bitrixId'])) {
	?>
			<script type="text/javascript">
				$('#myModal').modal('show');
			</script>
	<?php
		};
	?>

<!--