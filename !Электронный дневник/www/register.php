<?php
	header("Content-type: text/html; charset=utf-8");
	include('model.php');
	startup();
	if (isset($_POST['full_name'])) {
		$full_name = '\''.$_POST['full_name'].'\'';
		$addres = '\''.$_POST['addres'].'\'';
		$index = '\''.$_POST['index'].'\'';
		$email = '\''.$_POST['email'].'\'';
		$phone_number = '\''.$_POST['phone_number'].'\'';
		$director = '\''.$_POST['director'].'\'';
		$count_teachers = '\''.$_POST['count_teachers'].'\'';
		$count_students = '\''.$_POST['count_students'].'\'';
		$a = mysql_query("INSERT INTO about_school (full_name, addres, ind, email, phone_number, director, count_teachers, count_students)
			VALUES ($full_name, $addres, $index, $email, $phone_number, $director, $count_teachers, $count_students)");
		$id = mysql_insert_id();
		echo $id;
		session_start();
		$_SESSION['id_school'] = $id;
	};

	if (isset($_POST['who']) && $_POST['who'] == 'teacher') {
		// echo "string";
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_POST['id_school'].'\'';
		$subject = '\''.$_POST['subject'].'\'';
		$classes = '\''.$_POST['classes'].'\'';
		$phone_number = '\''.$_POST['phone_number'].'\'';
		$email = '\''.$_POST['email'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$a = mysql_query("INSERT INTO teachers (FIO, id_school, subject, classes, phone_number, email, login, password)
			VALUES ($fio, $id_school, $subject, $classes, $phone_number, $email, $login, $password)");
		// $id = mysql_insert_id();//id учителя
		// echo $id;
		// echo $a;
		// echo $id_school;
	};

	if (isset($_POST['who']) && $_POST['who'] == 'student') {
		// echo "string";
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_POST['id_school'].'\'';
		$class = '\''.$_POST['class_number'].'\'';
		$phone_number = '\''.$_POST['phone_number'].'\'';
		$addres = '\''.$_POST['address'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';

		$parent1 = '\''.$_POST['parent1'].'\'';
		$parent2 = '\''.$_POST['parent2'].'\'';
		$login_parent1 = '\''.$_POST['login_parent1'].'\'';
		$login_parent2 = '\''.$_POST['login_parent2'].'\'';
		$password_parent1 = '\''.md5($_POST['password_parent1']).'\'';
		$password_parent2 = '\''.md5($_POST['password_parent2']).'\'';
		// echo "$parent1".' '."$parent2".' '."$password_parent1".' '."$password_parent2";
		// echo "$fio".' '."$class".' '."$phone_number".' '."$address".' '."$login".' '."$password";
		$a = mysql_query("INSERT INTO students (FIO, id_school, class, phone_number, address, login, password)
			VALUES ($fio, $id_school, $class, $phone_number, $addres, $login, $password)");
		$id = mysql_insert_id();
		$q = mysql_query("INSERT INTO parents (FIO, id_child, id_school, login, password)
			VALUES ($parent1, $id, $id_school, $login_parent1, $password_parent1)");
		$q = mysql_query("INSERT INTO parents (FIO, id_child, id_school, login, password)
			VALUES ($parent2, $id, $id_school, $login_parent2, $password_parent2)");
	};

	if (isset($_POST['who']) && $_POST['who'] == 'admin') {
		// echo "string";
		$id_school = '\''.$_POST['id_school'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$a = mysql_query("INSERT INTO administrators (login, password, id_school)
			VALUES ($login, $password, $id_school)");
		// $id = mysql_insert_id();//id учителя
		// echo $id;
		// echo $a;
		// echo $id_school;
	};
	
	if (isset($_POST['act']) && $_POST['act'] == 'compliance') {
		$login = $_POST['login'];
		$id_school = $_POST['id_school'];
		$classes = $_POST['classes'];
		// echo "$login"."$id_school"."$classes";
		echo "string";
	}

?>