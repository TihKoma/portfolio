<?php

	class create {
		function getForm() {
			ob_start();
	 		include "/view/v.create.php";
	 		return ob_get_clean();
		}

		function addTask($userName, $email, $taskContent) {
			if ($this->checkValue($userName, $email, $taskContent))
				die("Форма не заполнена");
			$userName = '\''.$userName.'\'';
			$email = '\''.$email.'\'';
			$taskContent = '\''.$taskContent.'\'';

			$q = mysql_query("INSERT INTO tasklist (userName, email, taskContent, status) VALUES ($userName, $email, $taskContent, 0)");
			if ($q)
				header('Location: index.php');
		}

		function checkValue($userName, $email, $taskContent) {
			if (empty($userName) || empty($email) || empty($taskContent))
				return 1;
		}
	};

?>