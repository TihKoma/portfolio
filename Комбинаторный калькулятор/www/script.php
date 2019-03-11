<?php 

	header("Content-type: text/html; charset=utf-8");

	if ($_POST['action'] == 'start') {
		$date = date("d.m.y");
		$time = date("H:i:s");

		// setcookie("date", "$date");
		session_start();
		$_SESSION['date'] = $date;
		$_SESSION['time'] = $time;
		$_SESSION['content'] = "";
		// setcookie("time", "$time");
	};

	if ($_POST['action'] == 'save_and_exit') {
		$date = date("d.m.y");
		$time = date("H:i:s");

		$f = fopen("history.txt", "a+");

		session_start();
		$content = $_SESSION['content'];
		$date_start = $_SESSION['date'];
		$time_start = $_SESSION['time'];
		// Записать строку текста
		fwrite($f, "\r\nДата начала сессии - $date_start\r\nВремя начала сессии - $time_start\r\n\r\nВыполненные операции:$content\r\n\r\nДата конца сессии - $date\r\nВремя конца сессии - $time\r\n_________________________________________________\r\n"); 

		// Закрыть текстовый файл
		fclose($f);

		session_destroy();
		echo "success";
	};


	if ($_POST['action'] == 'save') {
		$number = $_POST['number'];
		$res = $_POST['res'];
		$operation = $_POST['operation'];
		session_start();
		// if (isset($_SESSION['date'])) {
		// 	echo "string";
		// } else{
		// 	echo "string2 ";
		// };
		$str = $_SESSION['content'];
		$_SESSION['content'] = "$str\r\n$operation числа $number = $res";
		// echo $operation;
		echo $_SESSION['content'];

	};

?>