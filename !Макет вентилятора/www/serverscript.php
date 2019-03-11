<?php 

include("model.php");
date_default_timezone_set("Europe/Moscow");

startup();

if (isset($_POST['get'])) {
	$table = $_POST['get'];
	$table = '`'.$table.'`';
	$q = "SELECT * FROM $table ORDER BY id Desc";
	$res = Select($q);
	foreach ($res as $key => $value) {
		$id = $value['id'];
		$a = $value['date'];
		$b = $value['source'];
		$c = $value['message'];
		$d = $value['status'];
		$index = $value['ind'];
		if ($txt!='') {
		//? - разделитель между строками, | - разделитель элементов строки
			$txt = $txt.'?'.$a.'|'.$b.'|'.$c.'|'.$d.'|'.$id.'|'.$index;
		} else {
			$txt = $a.'|'.$b.'|'.$c.'|'.$d.'|'.$id.'|'.$index;
		}
	}
	$q = "SELECT count(*) FROM $table";
	$amount = Select($q); 
	foreach ($amount as $key => $value) {
		$amount = $value['count(*)'];
	}
	// $amount = explode('\"', $amount);
	// var_dump($amount);
	$txt = $amount.'%'.$txt;
	var_dump($txt);
}

if (isset($_POST['insert']) && isset($_POST['table'])) {
	$txt = $_POST['insert'];
	$table = $_POST['table'];
	if (isset($_POST['status'])) {
		$status = $_POST['status'];
		$status = '\''.$status.'\'';
	};
	if (date("H") == 00) {
		$hours = 23;
	} else {
		$hours = date("H") - 1;
	}
	$date = $hours.':'.date("i").' '.date("d.m.Y");
	$date = '\''.$date.'\'';
	$txt = '\''.$txt.'\'';
	$q = "INSERT INTO `all` (date, source, message, status) VALUES ($date, 'П1', $txt, $status)";
	mysql_query($q) or die(mysql_error());
	$id = mysql_insert_id();
	$id = '\''.$id.'\'';

	if ($table == 'warning') {
		echo $table;
		$q = "INSERT INTO `warning` (date, source, message, status, ind) VALUES ($date, 'П1', $txt, $status, $id)";
		mysql_query($q) or die(mysql_error());
		return;
	}

	$table = '`'.$table.'`';
	$q = "INSERT INTO $table (date, source, message, status) VALUES ($date, 'П1', $txt, $status)";
	mysql_query($q) or die(mysql_error());
}

//Изменение строк в БД в таблице warning
if (isset($_POST['update']) && isset($_POST['id']) && isset($_POST['index'])) {
	$txt = $_POST['update']; //$txt - строка, которую нужно поместить в ячейку status с id = $id
	$txt = '\''.$txt.'\'';
	
	$id = $_POST['id'];
	$id = '\''.$id.'\'';
	
	$index = $_POST['index'];
	$index = '\''.$index.'\'';

	$q = "UPDATE `warning` SET status = $txt WHERE id = $id";
	mysql_query($q) or die(mysql_error());
	$q = "UPDATE `all` SET status = $txt WHERE id = $index";
	mysql_query($q) or die(mysql_error());
}


?>