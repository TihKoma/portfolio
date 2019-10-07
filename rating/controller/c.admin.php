<?php

	class Admin extends m_Admin {
		private static $instance;	// Экземпляр класса
		
		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new Admin();
				
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

// _______________________________________________Работа с DOM'ом (вывод вьюшек)_________________________________________________________________

		// Вывод вьюшки v.skills.php
		public function skills() {
			echo $this->template("view/admin/v.skills.php");
		}

		// Форма для добавления нового навыка
		public function skillsNew($param) {
			echo $this->template("view/admin/v.skills.new.php");
		}

		// Форма редактирования навыка
		public function skillsEdit($param) {
			echo $this->template("view/admin/v.skills.edit.php", array("skillId" => $param[0]));
		}
// ______________________________________________________________________________________________________________________________________________






// ______________________________________________Контроллеры, взаимодействие с БД (save, update, delete, etc.)___________________________________
		public function cSkillsAddNew() {
			$this->saveSkill($_POST['value'], $_POST['weight']);
		}

		public function cSkillsEdit() {
			$this->updateSkill($_POST['id'], $_POST['value'], $_POST['weight']);
			header("Location: http://".HOST_NAME."/admin/skills/");
		}

	};


?>