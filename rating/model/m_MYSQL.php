<?php
//
// Помощник работы с БД
//
class m_MYSQL {
	private static $instance;				// экземпляр класса
	private $link;							// Ссылка на соединение с БД
	
	//
	// Получение экземпляра класса
	// Результат - экземпляр класса m_MYSQL
	//
	public static function getInstance() {
		if (self::$instance == null)
			self::$instance = new m_MYSQL();
			
		return self::$instance;
	}

	public function __construct() {
		setlocale(LC_ALL, "ru_RU.UTF-8");
		mb_internal_encoding("UTF-8");

		$this->link = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
		mysqli_query($this->link, "set names utf8");
	}

	//
	// Выборка строк
	// $query    	- полный текст SQL запроса
	// результат	- массив выбранных объектов
	//
	public function Select($query) {
		$result = mysqli_query($this->link, $query);
		
		if (!$result)
			die(mysqli_error($this->link));
		
		$n = mysqli_num_rows($result);
		$arr = array();
	
		for ($i = 0; $i < $n; $i++) {
			$row = mysqli_fetch_assoc($result);		
			$arr[] = $row;
		};

		return $arr;				
	}
	
	//
	// Вставка строки
	// $table 		- имя таблицы
	// $object 		- ассоциативный массив с парами вида "имя столбца - значение"
	// Результат	- идентификатор новой строки
	//
	public function Insert($table, $object) {
		$columns = array();
		$values = array();
	
		foreach ($object as $key => $value) {
			$key = mysqli_real_escape_string($this->link, $key . '');
			$columns[] = $key;
			
			if ($value === null)
				$values[] = 'NULL';
			else {
				$value = mysqli_real_escape_string($this->link, $value . '');							
				$values[] = "'$value'";
			};
		};
		
		$columns_s = implode(',', $columns);
		$values_s = implode(',', $values);
			
		$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
		$result = mysqli_query($this->link, $query);
								
		if (!$result)
			die(mysqli_error($this->link));
			
		return mysqli_insert_id($this->link);
	}
	
	//
	// Изменение строк
	// $table 		- имя таблицы
	// $object 		- ассоциативный массив с парами вида "имя столбца - значение"
	// $where		- условие (часть SQL запроса)
	// Результат	- число измененных строк
	//	
	public function Update($table, $object, $where) {
		$sets = array();
	
		foreach ($object as $key => $value) {
			$key = mysqli_real_escape_string($this->link, $key . '');
			
			if ($value === null)
				$sets[] = "$key=NULL";			
			else {
				$value = mysqli_real_escape_string($this->link, $value . '');					
				$sets[] = "$key='$value'";			
			};		
		};
		
		$sets_s = implode(',', $sets);			
		$query = "UPDATE $table SET $sets_s WHERE $where";
		$result = mysqli_query($this->link, $query);
		
		if (!$result)
			die(mysqli_error($this->link));

		return mysqli_affected_rows($this->link);	
	}
	
	//
	// Удаление строк
	// $table 		- имя таблицы
	// $where		- условие (часть SQL запроса)	
	// Результат	- число удаленных строк
	//		
	public function Delete($table, $where) {
		$query = "DELETE FROM $table WHERE $where";		
		$result = mysqli_query($this->link, $query);
						
		if (!$result)
			die(mysqli_error($this->link));

		return mysqli_affected_rows($this->link);	
	}
}
