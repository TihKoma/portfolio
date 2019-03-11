<?php 

	header("Content-type: text/html; charset=utf-8");

	if ($_POST['action'] == 'save') {
		$number = $_POST['number'];
		$res = $_POST['res'];
		$date = date("d.m.y");
		$time = date("H:i:s");
		// Открыть текстовый файл
		$f = fopen("history.txt", "a+");

		// $text = $fgets($f);

		// Записать строку текста
		fwrite($f, "Переведено число $number в $res - $date $time\r\n"); 

		// fseek($f, 0, SEEK_END); // поместим указатель в конец

		// Закрыть текстовый файл
		fclose($f);

	};

?>