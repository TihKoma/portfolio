<?php
	header("Content-type: text/html; charset=utf-8");
	include('model.php');
	startup();

	if (isset($_POST['action']) && $_POST['action'] == 'scheduling') {
		$class = '\''.$_POST['class_number'].'\'';
		$monday = '\''.$_POST['monday'].'\'';
		$tuesday = '\''.$_POST['tuesday'].'\'';
		$wednesday = '\''.$_POST['wednesday'].'\'';
		$thursday = '\''.$_POST['thursday'].'\'';
		$friday = '\''.$_POST['friday'].'\'';
		$saturday = '\''.$_POST['saturday'].'\'';

		session_start();
		$id_school = '\''.$_SESSION['id_school'].'\'';
		echo "$id_school";
		// echo $class.$monday.$tuesday.$wednesday.$thursday.$friday.$saturday;

		$a = mysql_query("INSERT INTO schedule (class, id_school, monday, tuesday, wednesday, thursday, friday, saturday) 
			VALUES ($class, $id_school, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday)");
		echo $a;
	};
?>