<?php


	class m_User extends m_Base {
		private static $instance;	// Экземпляр класса
		
		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new m_User();
				
			return self::$instance;
		}

		public function __construct() {
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
			$method = $dirs[2];
			for ($i = 3; $i < count($dirs); $i++){
				if (is_numeric($dirs[$i])) {	// Если число -> добавляем в массив параметров $params
					array_push($params, $dirs[$i]);
					continue;
				};

				$method .= ucfirst($dirs[$i]);	// Заглавная буква нового слова
			};
			return 0;
		}

		// Записать в БД инф-ию о юзере
		protected function setUserInfo($file, $surname, $name, $patronymic, $position, $company, $experience, $sector, $head) {
			$bitrixId = $_COOKIE['bitrixId'];
			$path = 'view/user/img_user/';							// Пути загрузки файлов
			$types = array('image/png', 'image/jpeg');				// Массив допустимых значений типа файла
			$size = 1024000;										// Максимальный размер файла
			// $name = "";

			$values = array(
					'surname' => $surname,
					'name' => $name,
					'patronymic' => $patronymic,
					'position' => $position,
					'company' => $company,
					'experience' => $experience,
					'sector_id' => $sector,
					'head' => $head
				);

			// Обработка запроса
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				if (!empty($file['picture']['name'])) {
					// Проверяем тип файла
					if (!in_array($file['picture']['type'], $types))
						die('<p>Запрещённый тип файла</p>');

					// Проверяем размер файла
					if ($file['picture']['size'] > $size)
						die('<p>Слишком большой размер файла</p>');

					$name = $this->resize($file['picture']);

					// Загрузка файла и вывод сообщения
					if (!@copy($name, $path . $name))
						die("Error downloading img");
					// echo $name;
					$values['img'] = $name;
					// Удаляем временный файл
					unlink($name);
				};

				
				if (!$this->checkBitrixId($_COOKIE['bitrixId'])) { 					// Если юзер есть -> обновляем инфу, иначе добавляем нового
					$uid = $this->getUidByBitrixid($_COOKIE['bitrixId']);				
					$this->db->Update("users", $values, "bitrix_id = '$bitrixId'");
				} else {
					$values['bitrix_id'] = $bitrixId;
					$uid = $this->db->Insert("users", $values);
				};

				session_start();
				if (!setcookie("uid", $uid, time()+$this->LIFE_TIME_COOKIE, "/"))
					die("Error setting cookie (set_user_info)");
				$_COOKIE['uid'] = $uid;

			};


		}

		// Функция изменения размера
		// Изменяет размер изображения
		// quality - качество изображения (по умолчанию 75%)
		private function resize($file, $quality = null) {
			global $tmp_path;

			// Ограничение по высоте в пикселях
			$max_thumb_size = 94;
			// $max_size = 600;

			// Качество изображения по умолчанию
			if ($quality == null)
				$quality = 75;

			// Cоздаём исходное изображение на основе исходного файла
			if ($file['type'] == 'image/jpeg')
				$source = imagecreatefromjpeg($file['tmp_name']);
			elseif ($file['type'] == 'image/png')
				$source = imagecreatefrompng($file['tmp_name']);
			elseif ($file['type'] == 'image/gif')
				$source = imagecreatefromgif($file['tmp_name']);
			else
				return false;

			$src = $source;

			// Определяем ширину и высоту изображения
			$w_src = imagesx($src); 
			$h_src = imagesy($src);

			$w = $max_thumb_size;

			//Получить расширение файла
			$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

			//Поставить новое имя файла с расширением загружаемого файла
			$file['name'] = $_COOKIE['bitrixId'].'.'.$extension;

			// Если высота больше заданной
			if ($h_src > $w) {
				// Вычисление пропорций
				$ratio = $h_src/$w;
				$w_dest = round($w_src/$ratio);
				$h_dest = round($h_src/$ratio);

				// Создаём пустую картинку
				$dest = imagecreatetruecolor($w_dest, $h_dest);

				// Копируем старое изображение в новое с изменением параметров
				imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

				// Вывод картинки и очистка памяти
				imagejpeg($dest, $tmp_path . $file['name'], $quality);
				imagedestroy($dest);
				imagedestroy($src);

				return $file['name'];
			} else {
				// Вывод картинки и очистка памяти
				imagejpeg($src, $tmp_path . $file['name'], $quality);
				imagedestroy($src);

				return $file['name'];
			};
		}

		// Получить uid юзера по его bitrixId
		protected function getUidByBitrixid($bitrixId) {
			$uid = 0;
			$rows = $this->db->Select("SELECT * FROM users WHERE bitrix_id = $bitrixId");
			
			$uid = $rows[0]['id'];

			return $uid;
		}

		// Записать в БД список навыков юзера
		protected function setUserSkills($skills) {
			if (!$this->checkSkillsValue($_COOKIE['uid'], $skills)) // Если список навыков остался прежним -> return
				return;

			$uid = $_COOKIE['uid'];
			$this->db->Delete("user_skills", "uid = '$uid'");
			
			$cnt = count($skills);
			for ($i = 0; $i < $cnt; $i++) {
				$values = array(
					'uid' => $uid,
					'skill_id' => $skills[$i]
				);

				$this->db->Insert("user_skills", $values);
			};

		}

		// Проверяет на совпадение нового списка навыков и списка навыков из БД у пользователя с uid = $uid
		// $skills - поступивший новый список
		// 0 - навыки не изменены, 1 - изменены
		protected function checkSkillsValue($uid, $skills) {
			$i = 0;
			$sum = 0;
			$uid = $_COOKIE['uid'];

			$rows = $this->db->Select("SELECT * FROM user_skills WHERE uid = $uid");
			$cnt = count($rows);

			for ($i = 0; $i < $cnt; $i++)
				if (in_array($rows[$i]['skill_id'], $skills))
					$sum++;

			if ($sum == count($skills) && $sum == $cnt)
				return 0;
			return 1;
		}

		// Записать в БД процентаж навыков текущего юзера
		protected function setPercent($skillId, $skillPercent) {
			$uid = $_COOKIE['uid'];
			$cnt = count($skillId);

			for ($i = 0; $i < $cnt; $i++) {
				$values = array('percent' => $skillPercent[$i]);
				$this->db->Update("user_skills", $values, "skill_id = '$skillId[$i]' AND uid = '$uid'");
			};

		}

		// Обновить рейтинговый список (Навыки*процентаж + Тест)
		protected function updateUserRating() {
			$uid = $_COOKIE['uid'];
			$score = $this->getInfoScore();								// Посчитать баллы за заполненную анкету
			$score += $this->getSkillScore();							// Посчитать баллы за скиллы и их процентаж
			$score += $this->getTestScore();							// Посчитать баллы за тест

			$values = array(
				'uid' => $uid,
				'score' => $score
			);

			$query = "SELECT count(*) FROM rating_list WHERE uid = '$uid'";
			if ($this->db->Select($query)[0]['count(*)'] > 0)
				$this->db->Update("rating_list", $values, "uid = '$uid'");
			else 
				$this->db->Insert("rating_list", $values);
		}

		// Посчитать баллы за анкету
		// return: $score
		private function getInfoScore() {
			$score = 0;
			if ($this->checkUserInfo())
				return 0;				// Анкета не заполнена
			return 1;					// Балл за анкету
		}

		// Посчитать баллы за скиллы текущего юзера (вес * процентаж)
		// return: $score
		private function getSkillScore() {
			$score = 0;
			$uid = $_COOKIE['uid'];

			$query = "	SELECT skills.weight, user_skills.percent
						FROM user_skills
						INNER JOIN skills ON user_skills.skill_id = skills.id
						WHERE uid = $uid";
			$rows = $this->db->Select($query);

			$cnt = count($rows);
			for ($i = 0; $i < $cnt; $i++)
				$score += $rows[$i]['weight']*$rows[$i]['percent'];	// вес*процент + ..
			return $score;
		}

		// Посчитать баллы за тест
		// return: $score
		private function getTestScore() {
			$score = 0;
			$uid = $_COOKIE['uid'];

			$query = "	SELECT answers.score
						FROM user_test
						INNER JOIN answers ON user_test.answer_id = answers.id
						WHERE uid = $uid";
			$rows = $this->db->Select($query);									// Получить все строки с баллами ответов, указанными текущим юзером

			$cnt = count($rows);
			for ($i = 0; $i < $cnt; $i++)
				$score += $rows[$i]['score'];									// score + score..
			return $score;
		}

		// Записать в БД ответ юзера
		// $questionId - id заданного вопроса, $answerId - ответ (id) юзера на этот вопрос
		protected function setAnswer($questionId, $answerId) {
			$uid = $_COOKIE['uid'];
			$values = array(
				'answer_id' => $answerId
			);
			$this->db->Update("user_test", $values, "uid = '$uid' AND question_id = '$questionId'");
		}


	};


?>