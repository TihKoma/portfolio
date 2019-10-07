<?php

	// Модель взаимодействия страницы с БД (для отображения вьюшек)

	class m_Page extends m_Base {
		private static $instance;	// Экземпляр класса
		protected $masQuestions;	// Массив с вопросами для теста
		const COUNT_QUESTIONS = 21;	// Кол-во вопросов в тесте
		
		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new m_Page();
				
			return self::$instance;
		}

		public function __construct() {
			$this->masQuestions = [];
			parent::__construct();
		}
		
		//
		// Генерация имени метода для вызова
		// Имя метода собирается из url (ЧПУ). Ex: domen.ru/admin/skills/edit/12 -> skillsEdit(12)
		// $dirs - распаршенный массив из url
		// Выходные данные:
		// $method - строка с именем метода, $params - массив с параметрами для передачи в метод
		public function getMethod($dirs, &$method, &$params) {
			$params = [];
			$method = (empty($dirs[1])) ? "Index" : "";

			for ($i = 1; $i < count($dirs); $i++){
				if (is_numeric($dirs[$i])) {	// Если число -> добавляем в массив параметров $params
					array_push($params, $dirs[$i]);
					continue;
				};

				$method .= ucfirst($dirs[$i]);	// Заглавная буква нового слова
			};
			$method = "print".$method;

			return 0;
		}

		// // Получить рейтинговый список
		// protected function getRatingList() {
		// 	return $this->db->Select("SELECT users.surname, users.name, users.patronymic, rating_list.score FROM rating_list INNER JOIN users ON rating_list.uid = users.id ORDER BY score DESC");
		// }

		// Получить список всех отраслей
		protected function getListSectors() {
			return $this->db->Select("SELECT * FROM sector");
		}

		// Получить полный список скиллов
		protected function getListSkills() {
			return $this->db->Select("SELECT * FROM skills");
		}

		// Получить скиллы юзера с uid = $uid
		protected function getUserSkills($uid) {
			return $this->db->Select("SELECT skills.id, skills.value, user_skills.percent FROM user_skills INNER JOIN skills ON user_skills.skill_id = skills.id WHERE uid = $uid");
		}

		// Получить инф-ию о юзере по его bitrixId
		protected function getUserInfo($bitrixId) {
			$masInfo = array();
			$rows = $this->db->Select("SELECT * FROM users WHERE bitrix_id = $bitrixId");

			foreach ($rows[0] as $key => $value)
				$masInfo["$key"] = $value;

			return $masInfo;
		}

		// Список вопросов уже сформирован? Проверка на его наличие в БД
		// Результат: 1 - список сформирован, 0 - нет
		private function issetQuestions() {
			$uid = $_COOKIE['uid'];
			$count = $this->db->Select("SELECT count(*) FROM user_test WHERE uid = '$uid'")[0]['count(*)'];
			if ($count == 21)
				return 1;
			return 0;
		}

		// Взять список вопросов (с их id) из БД и записать в массив masQuestions
		private function setMasQuestionsFromDB() {
			$uid = $_COOKIE['uid'];
			$res = $this->db->Select("SELECT user_test.question_id id FROM user_test WHERE uid = '$uid'");

			$cnt = count($res);
			for ($i = 0; $i < $cnt; $i++)
				array_push($this->masQuestions, $res[$i]['id']);
		}

		// Формирование массива с вопросами и фиксация его в БД
		protected function generateMasQuestions() {
			if (!empty($this->masQuestions))					// Если массив уже сформирован -> return
				return;

			if ($this->issetQuestions()) {						// Если в БД уже есть вопросы текущего юзера -> получить их и return
				$this->setMasQuestionsFromDB();					// Заполнить массив с вопросами
				return;
			};
			$userSkills = $this->getUserSkills($_COOKIE['uid']);

			if (count($userSkills) != 7) 						// Кол-во навыков юзера всегда 7
				die("Error. Count user skills is not 7. ".count($userSkills));

			while (count($this->masQuestions) < self::COUNT_QUESTIONS){
				$this->masQuestions = $this->get7Questions($this->masQuestions, $userSkills);

			$this->saveMasQuestions(); 							// Сохраняем сформированный массив в БД
		}

		// Получить блок (массив) из 7 вопросов
		private function get7Questions($mas, $userSkills) {
			for ($i = 0; $i < 7; $i++)
				array_push($mas, $this->getQuestionIdBySkillId($mas, $userSkills[$i]['id']));

			return $mas;
		}

		// Получить вопрос (id) с привязкой к навыку с id = $skillId
		private function getQuestionIdBySkillId($mas, $skillId) {
			$i = 0;
			$query = "	SELECT questions.id
						FROM questions
						WHERE skill_id = $skillId";
			$res = $this->db->Select($query);							// Поиск вопроса с привязкой к навыку с id = $skillId
			$cnt = count($res);
			if (!empty($res)) {
				while (in_array($res[$i]['id'], $mas) && $i < $cnt)		// Ищем индекс элемента массива, который отсутствует в masQuestions
					$i++;
				
				if ($i < $cnt)											// Если не вышли за пределы массива -> return id вопроса
					return $res[$i]['id'];
			};

			$i = 0;
			$query = "	SELECT questions.id
						FROM questions
						WHERE skill_id = '-1'";
			$res = $this->db->Select($query);							// Если не нашли вопрос с привязкой к навыку -> поиск вопроса без привязки
			$cnt = count($res);
			
			while (in_array($res[$i]['id'], $mas) && $i < $cnt)
				$i++;
			
			if ($i < $cnt)												// Если не вышли за пределы массива -> return id вопроса
				return $res[$i]['id'];

			die("Error. Question with this skill not found.");
		}

		// Сохранить массив с вопросами в БД
		private function saveMasQuestions() {
			$cnt = count($this->masQuestions);
			for ($i = 0; $i < $cnt; $i++) {
				$values = array("uid" => $_COOKIE['uid'],
								"question_id" => $this->masQuestions[$i],
								"answer_id" => -1);

				$this->db->Insert("user_test", $values);
			};
		}

		// Получить контент вопроса (вопрос + возможные ответы)
		protected function getContentQuestionById($id) {
			$query = "	SELECT 	questions.id questionId, questions.value question, ans1.id ans1Id, ans1.value ans1, ans2.id ans2Id, ans2.value ans2,
								ans3.id ans3Id, ans3.value ans3, ans4.id ans4Id, ans4.value ans4
						FROM questions
						INNER JOIN answers ans1 ON questions.ans1_id = ans1.id
						JOIN answers ans2 ON questions.ans2_id = ans2.id
						JOIN answers ans3 ON questions.ans3_id = ans3.id
						JOIN answers ans4 ON questions.ans4_id = ans4.id
						WHERE questions.id = $id";
						// echo $id;
			return $this->db->Select($query)[0];
		}

		// Проверить заполнил ли юзер скиллы на пред. шаге
		protected function checkUserSkills() {
			$uid = $_COOKIE['uid'];
			$skills = $this->db->Select("SELECT * FROM user_skills WHERE uid = $uid");
			$count = count($skills);

			if ($count == 7)
				return;
			header("Location: http://".HOST_NAME."/skills?uncompletedStep");
		}


		// Проверить распределены ли 100% на пред. шаге
		protected function checkUserPercent() {
			$uid = $_COOKIE['uid'];
			$res = $this->db->Select("SELECT * FROM user_skills WHERE uid = $uid");
			
			$count = count($res);
			$sumPercent = 0;										// Сумма процентажа

			for ($i = 0; $i < $count; $i++)
				$sumPercent += $res[$i]['percent'];
			$sumPercent = sprintf("%f", $sumPercent);

			if ($sumPercent == 100)
				return;
			header("Location: http://".HOST_NAME."/percent?uncompletedStep");
		}

		// Проверить прошел ли юзер тест на пред. шаге
		protected function checkUserTest() {
			$uid = $_COOKIE['uid'];
			$res = $this->db->Select("SELECT * FROM user_test WHERE uid = $uid");

			$count = count($res);
			$cntAnswers = 0;										// Кол-во ответов

			for ($i = 0; $i < $count; $i++)
				$cntAnswers += ($res[$i]['answer_id'] != -1) ? 1 : 0;

			if ($cntAnswers == $count && $count == 21)
				return;
			header("Location: http://".HOST_NAME."/test?uncompletedStep");
		}

		// Ответил на вопрос юзер
		// Входные данные: $num - индекс массива текущего вопроса
		// return: 0 - если остаться на этом вопросе, иначе номер вопроса для редиректа
		protected function checkAnswer($num) {
			$this->checkBoundsMasQuestion($num);

			$uid = $_COOKIE['uid'];
			$questionId = $this->masQuestions[$num];

			$query = "	SELECT user_test.answer_id
						FROM user_test
						WHERE uid = $uid
						AND question_id = $questionId";
			$res = $this->db->Select($query)[0];

			if ($res['answer_id'] != -1)				// Если этот вопрос уже с ответом
				return $this->checkAnswer(++$num);		// Чекаем следующий

			return $num;								// Иначе возвращаем номер вопроса (он без ответа)
		}

		// Проверка выхода за границы массива masQuestion
		protected function checkBoundsMasQuestion($num) {
			if ($num >= count($this->masQuestions) || $num < 0)
				header("Location: http://".HOST_NAME."/essay/");
		}

		// Сформирован ли тест
		protected function issetTest() {
			$uid = $_COOKIE['uid'];

			$query = "	SELECT count(*)
						FROM user_test
						WHERE uid = $uid";
			if ($this->db->Select($query)[0]['count(*)'] > 0)
				return 1;										// Сформирован
			return 0;											// Нет
		}

		// Получить список участников (рейтинг)
		protected function getRatingList() {
			$query = "	SELECT users.id, users.surname, users.name, users.patronymic, users.img, users.position, rating_list.score
						FROM rating_list
						INNER JOIN users ON rating_list.uid = users.id
						ORDER BY rating_list.score DESC";

			return $this->db->Select($query);
		}

	};


?>