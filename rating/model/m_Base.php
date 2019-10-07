<?php

	// m_Base - общие расчеты

	abstract class m_Base {
		protected $LIFE_TIME_COOKIE = 604800; 	// Время жизни куков (3600*24*7)
		protected $db;							// Драйвер БД

		abstract public function getMethod($dirs, &$method, &$params);

		public function __construct() {
			$this->db = m_MYSQL::getInstance(); // Получаем экземпляр класса m_MYSQL
		}


		// Обработка вызова несуществующего метода
		final public function __call($name, array $params) {
			die("Oops. Call to undefined method - ".$name);
		}


		public function customRound($i) {
			$i *= 100;
			$i = floor($i);
			return $i / 100;
		}

		// Распределение элементов массива до сотых (sum = 100)
		protected function distribute($mas) {
			$mas = $this->roundMas($mas); // Отбрасывание дробной части элементов массива (до сотых)
			$sum = $this->getSum($mas);

			if (abs(100 - $sum) > 0.1)
				die("Error. The sum of the percentages of the skills are not 100 (distribute)");

			$mas[$this->getMax($mas)] += 100 - $sum;
			$sum = $this->getSum($mas);

			if ((int) $sum != 100)
				$mas = $this->distribute($mas);

			return $mas;
		}

		protected function roundMas($mas) {
			$cnt = count($mas);
			for ($i = 0; $i < $cnt; $i++)
				$mas[$i] = $this->customRound($mas[$i]);
			return $mas;
		}

		protected function getSum($mas) {
			$sum = 0;
			$cnt = count($mas);
			for ($i = 0; $i < $cnt; $i++)
				$sum += $mas[$i];
			return $sum;
		}

		protected function getMax($mas) {
			$max = 0;
			for ($i = 0; $i < count($mas); $i++)
				if ($mas[$i] > $mas[$max])
					$max = $i;
			return $max;
		}

		protected function dumpArray($mas) {
			$cnt = count($mas);
			for ($i = 0; $i < $cnt; $i++) {
				if (is_array($mas[$i]))
					$this->dumpArray($mas[$i]);
				else
					echo $mas[$i].";  ";
			};
			echo "<br>";
		}

		// Проверяет наличие элемента $id в массиве $mas
		// 0 - $id принадлежит массиву, -1 - нет
		// $mas - двумерный, $mas[$i]['id'], i=[0..$cnt)
		protected function idInUserSkills($id, $mas) {
			$cnt = count($mas);
			for ($i = 0; $i < $cnt; $i++)
				if ($id == $mas[$i]['id'])
					return 0;
			return -1;;
		}

		// Проверяет установлены ли проценты у навыков пользователя (sum == 100 ?)
		// $mas[$i][2] - percent, $i=[0, $cnt)
		protected function checkSumPercent($mas) {
			$cnt = count($mas);
			$sum = 0;

			for ($i = 0; $i < $cnt; $i++)
				$sum += $mas[$i]['percent'];

			$sum = sprintf("%f", $sum);

			if ($sum == 100)
				return 0;
			return -1;
		}

		protected function checkBitrixId($bitrixId) {
			$rows = $this->db->Select("SELECT * FROM users WHERE bitrix_id = $bitrixId");
			if (count($rows) > 0)
				return 0;
			return -1;
		}

		protected function issetCookieBitrixId() {
			if (!isset($_COOKIE['bitrixId']))
				header("Location: http://".HOST_NAME."?unreg=1");
		}

		protected function issetCookies() {
			$this->issetCookieBitrixId();
			$bitrixId = $_COOKIE['bitrixId'];

			if (!isset($_COOKIE['uid'])) {
				$rows = $this->db->Select("SELECT users.id FROM users WHERE bitrix_id = $bitrixId");
				if (count($rows) > 0) {
					// echo '|'.$rows[0]['id'].'|';
					// if (empty($rows[0]['id']))
						// header("Location: http://".HOST_NAME."/profile");

					setcookie("uid", $rows[0]['id'], time()+$this->LIFE_TIME_COOKIE, "/");
					$_COOKIE['uid'] = $rows[0]['id'];
					return;
				};

				header("Location: http://".HOST_NAME."/profile?uncompletedStep");
				
			};
		}

		// Проверить заполнена ли инф-ия о текущем юзере
		protected function checkUserInfo() {
			$uid = $_COOKIE['uid'];
			$info = $this->db->Select("SELECT * FROM users WHERE id = $uid")[0];
			foreach ($info as $key => $value)
				if ($this->emptyField($value))
					return 1;
			return 0;
		}

		protected function emptyField($perem) {
			if ($perem == "")
				return 1;
			return 0;
		}

	};

?>