<?php

	class taskList {
		protected $userName, $email, $taskContent, $status;

		//$param - по какому параметру сортировка
		function view_list($currentPage, $COUNT, $param = 'id') {

			$this->userName = $this->email = $this->taskContent = $this->status = array();

			$shift = ($currentPage - 1)*$COUNT;

			$array = mysql_fetch_array(mysql_query("SELECT count(*) FROM tasklist"));
			$countRecord=$array[0]; //количество записей

			$q = mysql_query("SELECT * FROM tasklist ORDER BY $param LIMIT $shift, $COUNT");

			while ($row = mysql_fetch_assoc($q)){
				$this->userName[] = $row['userName'];
				$this->email[] = $row['email'];
				$this->taskContent[] = $row['taskContent'];
				$this->status[] = $row['status'];
			};

			ob_start();
	 		include "/view/v.task.list.php";
	 		return ob_get_clean();
		}

	};


?>