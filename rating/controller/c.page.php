<?php
	// Вывод на печать вьюшек

	class Page extends m_Page {
		private static $instance;

		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new Page();
				
			return self::$instance;
		}

		public function __construct() {
			parent::__construct();
		}

		//
		// Генерация HTML шаблона в строку.
		//
		protected function template($fileName, $vars = array()) {
			// Установка переменных для шаблона.
			foreach ($vars as $k => $v)
				$$k = $v;

			// Генерация HTML в строку.
			ob_start();
			include "$fileName";
			return ob_get_clean();
		}	

		// Вывод главной страницы
		public function printIndex() {
			if (isset($_GET['bitrixid']))
				if (!setcookie("bitrixId", $_GET['bitrixid'], time()+$this->LIFE_TIME_COOKIE, "/"))
					die("Error setting cookies (printIndex)");
				else
					header("Location: http://".HOST_NAME);
			$ratingList = $this->getRatingList();

			echo $this->template("view/user/v.index.php", array("ratingList" => $ratingList));
		}

		// Вывод страницы профиля юзера
		public function printProfile() {
			$this->issetCookieBitrixId();

			$listSectors = $this->getListSectors();
			$userInfo = array(
				"surname" => "",
				"name" => "",
				"patronymic" => "",
				"position" => "",
				"company" => "",
				"experience" => "",
				"sector_id" => "",
				"head" => ""
			);

			if (!($this->checkBitrixId($_COOKIE['bitrixId'])))		// Если юзер есть -> достаём его данные из БД
				$userInfo = $this->getUserInfo($_COOKIE['bitrixId']);
			echo $this->template("view/user/v.profile.php", array(	"listSectors" => $listSectors,
																	"userInfo" => $userInfo));
		}

		// Вывод вьюшки списка с навыками
		public function printSkills() {
			$this->issetCookies();													// Установлены ли все нужные куки (bitrixId, uid)
			if ($this->checkUserInfo())												// Если не указана инф-ия о себе на пред. шаге ->
				header("Location: http://".HOST_NAME."/profile?uncompletedStep");	// -> возврат к анкете

			$userSkills = array();
			if (!$this->checkBitrixId($_COOKIE['bitrixId']))						// Если юзер есть -> получаем его скиллы
				$userSkills = $this->getUserSkills($_COOKIE['uid']);
	 		$listSkills = $this->getListSkills();
			
			echo $this->template("view/user/v.skills.php", array(	"userSkills" => $userSkills,
																	"listSkills" => $listSkills));
		}

		// Вывод страницы с процентажем скиллов
		public function printPercent() {
			$this->issetCookies();									// Установлены ли все нужные куки (bitrixId, uid)
	 		$this->checkUserSkills();								// Указаны ли скиллы на пред. шаге

	 		$userSkills = $this->getUserSkills($_COOKIE['uid']);
	 		
			echo $this->template("view/user/v.percent.php", array("userSkills" => $userSkills));
		}

		// Вывод теста (главная страница)
		public function printTest() {
			$this->issetCookies();									// Установлены ли все нужные куки (bitrixId, uid)
			$this->checkUserPercent();								// Распределены ли 100% на пред. шаге
			$issetTest = $this->issetTest();						// Сформирован ли тест

			echo $this->template("view/user/v.test.php", array('issetTest' => $issetTest));
		}

		// Вывод теста (главная страница)
		public function printEssay() {
			$this->issetCookies();									// Установлены ли все нужные куки (bitrixId, uid)
	 		$this->checkUserTest();									// Пройден ли тест на пред. шаге
			
			echo $this->template("view/user/v.essay.php");
		}

		// Вывод страницы с конкретным вопросом, где $num - его номер
		public function printTestQuestion($param) {
			$num = $param[0]-1;															// Номер вопроса (индекс массива)
			$this->issetCookies();														// Установлены ли все нужные куки (bitrixId, uid)
			$this->generateMasQuestions(); 												// Формирование массива с вопросами

			$this->checkBoundsMasQuestion($num);										// Проверить выход за границы массива

			$linkNum = $this->checkAnswer($num);
			if ($linkNum != $num)
				header("Location: http://".HOST_NAME."/test/question/".++$linkNum);

			$contentQuestion = $this->getContentQuestionById($this->masQuestions[$num]); 	// Получаем содержимое вопроса по его id

			echo $this->template("view/user/v.question.php", array(	"questionId" => $contentQuestion['questionId'],
																	"question" => $contentQuestion['question'],
																	"ans1" => $contentQuestion['ans1'],
																	"ans2" => $contentQuestion['ans2'],
																	"ans3" => $contentQuestion['ans3'],
																	"ans4" => $contentQuestion['ans4'],
																	"ans1Id" => $contentQuestion['ans1Id'],
																	"ans2Id" => $contentQuestion['ans2Id'],
																	"ans3Id" => $contentQuestion['ans3Id'],
																	"ans4Id" => $contentQuestion['ans4Id'],
																	"num" => $param[0]));
		}

		// // Вывод страницы с общим рейтингом
		// public function printRating() {
		// 	$ratingList = $this->getRatingList();
		// 	echo $this->template("view/user/v.rating.php", array("ratingList" => $ratingList));
		// }
	};


?>