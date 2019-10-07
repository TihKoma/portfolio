<?php

	class m_Admin {
		private static $instance;	// Экземпляр класса
		protected $db;				// Драйвер БД
		
		//
		// Получение экземпляра класса
		// Результат - экземпляр текущего класса
		//
		public static function getInstance() {
			if (self::$instance == null)
				self::$instance = new m_Admin();
				
			return self::$instance;
		}

		public function __construct() {
			$this->db = m_MYSQL::getInstance(); // Получаем экземпляр класса m_MYSQL
		}

		// Обработка вызова несуществующего метода
		final public function __call($name, array $params) {
			die("Oops. Call to undefined method - ".$name);
		}


		// Сохранение (добавление в БД) нового навыка
		protected function saveSkill($value, $weight) {
			$values = array(
				"value" => $value,
				"weight" => $weight
			);
			$this->db->Insert("skills", $values);
		}

		// Получить список всех навыков (id, value, weight)
		protected function getListSkills() {
			return $this->db->Select("SELECT * FROM skills");
		}

		// Получить информацию о конкретном навыке
		protected function getSkillInfo($id) {
			return $this->db->Select("SELECT * FROM skills WHERE id = '$id'");
		}

		// Обновление инф-ии конкретного навыка
		protected function updateSkill($id, $value, $weight) {
			$values = array(
				"value" => $value,
				"weight" => $weight
			);
			$this->db->Update("skills", $values, "id = $id");
		}
	};


?>