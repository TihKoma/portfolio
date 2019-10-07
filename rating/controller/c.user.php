<?php

// Обработка скрытых запросов вида: "/server/.."

	class User extends m_User{
		private static $instance;

		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new User();
				
			return self::$instance;
		}

		public function __construct() {
			parent::__construct();
		}

		// Сохранить инф-ию о юзере в БД
		public function saveInfo() {
			$this->setUserInfo($_FILES, $_POST['surname'], $_POST['name'], $_POST['patronymic'], $_POST['position'], $_POST['company'], $_POST['experience'], $_POST['sector'], $_POST['head']);
			$this->updateUserRating();
			
			header("Location: http://".HOST_NAME."/skills/");
		}

		// Сохранить навыки текущего юзера
		public function saveSkills() {
			if (!isset($_POST['skills']))
				die("Вы не выбрали ни одного навыка");

			if (count($_POST['skills']) > 7)
				die("Выберите не более 7 навыков");


			$this->setUserSkills($_POST['skills']);

			header("Location: http://".HOST_NAME."/percent/");
		}

		// Сохранить процентаж навыков текущего юзера
		public function savePercent() {
			if (!isset($_POST['skillPercent']))
				die("skillPercent undefined");

			$masPercent = $this->distribute($_POST['skillPercent']);

			$this->setPercent($_POST['skillId'], $masPercent);
			$this->updateUserRating();

			header("Location: http://".HOST_NAME."/test/");
		}

		// Сохранить ответ юзера в БД
		public function saveAnswer($param) {
			if (!isset($_POST['answerId']))
				die("Выберите вариант ответа");

			$num = $param[0]+1;											// Номер следующего вопроса
			$this->setAnswer($_POST['questionId'], $_POST['answerId']);	// Сохранить выбранный ответ в БД
			$this->updateUserRating();									// Обновить рейтинг текущего пользователя

			header("Location: http://".HOST_NAME."/test/question/".$num);
		}

		public function logout() {
			setcookie("bitrixId", "", time()-3600, "/");
			setcookie("uid", "", time()-3600, "/");
			setcookie("token", "", time()-3600, "/");

			unset($_COOKIE['bitrixId']);
			unset($_COOKIE['uid']);
			unset($_COOKIE['token']);

			header("Location: https://id2.action-media.ru/Account/LogOff");
		}

		public function sort() {
			if (!isset($_POST['experience']) || !isset($_POST['sector']))
				die("-1");

			$exp = ($_POST['experience'] == -1) ? "нетю(" : "есть)";

			$exp = $_POST['experience'];
			$sector = $_POST['sector'];

			if ($exp == -1 && $sector == -1)
				$where = "";
			elseif ($exp == -1 || $sector == -1)
				$where = "WHERE ".(($exp == -1) ? "sector_id = ".$sector : "experience = ".$exp);
			else
				$where = "WHERE sector_id = ".$sector." AND experience = ".$exp;


			$query = "	SELECT users.surname, users.name, users.patronymic, users.img, users.position, rating_list.score
						FROM rating_list
						INNER JOIN users ON rating_list.uid = users.id
						$where
						ORDER BY rating_list.score DESC";

			$ratingList = $this->db->Select($query);
			if (count($ratingList) == 0)
				die("<p>В данной категории пока нет участников. Станьте первым.</p>");
?>
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
?>
				<div class="row hidden-sm hidden-xs" style="margin-top: 10px;">
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
		}
	};


?>