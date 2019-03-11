<?php 

	header("Content-type: text/html; charset=utf-8");

	if ($_POST['action'] == 'save') {
		$number = $_POST['number'];
		$res = $_POST['res'];
		$date = date("d.m.y");
		$time = date("H:i:s");
		// Открыть текстовый файл
		$f = fopen("history.txt", "a+");

		// Записать строку текста
		fwrite($f, "Переведена дробь $number в $res - $date $time\r\n"); 

		// Закрыть текстовый файл
		fclose($f);

	};

?>