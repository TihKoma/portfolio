<?php
	include "model.php";
	startup();

	$surname = '\''.$_POST['surname'].'\'';
	$name = '\''.$_POST['name'].'\'';
	$patronymic = '\''.$_POST['patronymic'].'\'';
	// $sex = '\''.$_POST['sex'].'\'';
	$street = '\''.$_POST['street'].'\'';
	$house = $_POST['house'];
	$building = $_POST['building'];
	$room = $_POST['room'];
	$phone = '\''.$_POST['phone'].'\'';
	$email = '\''.$_POST['email'].'\'';
	$place_job = '\''.$_POST['place_job'].'\'';
	$position = '\''.$_POST['position'].'\'';

	$sex = ($_POST['sex'] == "Мужской") ? 0 : 1;


//============================фамилия==================================================
	$q = mysql_query("SELECT * FROM surname WHERE surname = $surname");
	// echo $surname.' |'.$q.'|';

	if (mysql_num_rows($q)>0){
		//забираем уже существующее значение
		while ($row = mysql_fetch_assoc($q))
			$s_id = '\''.$row['s_id'].'\''; //surname id
		// echo "1".$s_id;
	} else {
		//добавляем новое
		$z = mysql_query("INSERT INTO surname (surname) VALUES ($surname)");
		$s_id = '\''.mysql_insert_id().'\'';
		// echo "2".$s_id;
	};

//=================================Имя=====================================================
	$t = mysql_query("SELECT * FROM name WHERE name = $name");

	if (mysql_num_rows($t)>0){
		//забираем уже существующее значение
		while ($row = mysql_fetch_assoc($t))
			$n_id = '\''.$row['n_id'].'\''; //name id
	} else {
		//добавляем новое
		$p = mysql_query("INSERT INTO name (name) VALUES ($name)");
		$n_id = '\''.mysql_insert_id().'\'';
	};

//=================================Отчество=====================================================
	$y = mysql_query("SELECT * FROM patronymic WHERE patronymic = $patronymic");

	if (mysql_num_rows($y)>0){
		//забираем уже существующее значение
		while ($row = mysql_fetch_assoc($y))
			$p_id = '\''.$row['p_id'].'\''; //patronymic id
	} else {
		//добавляем новое
		$m = mysql_query("INSERT INTO patronymic (patronymic) VALUES ($patronymic)");
		$p_id = '\''.mysql_insert_id().'\'';
	};

//=================================Улица=====================================================
	$x = mysql_query("SELECT * FROM street WHERE street = $street");

	if (mysql_num_rows($x)>0){
		//забираем уже существующее значение
		while ($row = mysql_fetch_assoc($x))
			$st_id = '\''.$row['st_id'].'\''; //street id
	} else {
		//добавляем новое
		$w = mysql_query("INSERT INTO street (street) VALUES ($street)");
		$st_id = '\''.mysql_insert_id().'\'';
	};




	// $r = mysql_query("INSERT INTO name (n_id, name) VALUES ($id, $name)");
	// $t = mysql_query("INSERT INTO patronymic (p_id, patronymic) VALUES ($id, $patronymic)");
	// $y = mysql_query("INSERT INTO street (st_id, street) VALUES ($id, $street)");
	$f = mysql_query("INSERT INTO job (place_job, position) VALUES ($place_job, $position)");
	$j_id = '\''.mysql_insert_id().'\'';

	$u = mysql_query("INSERT INTO main (s_id, n_id, p_id, sex, st_id, house, building, room, phone, email, j_id) VALUES ($s_id, $n_id, $p_id, $sex, $st_id, $house, $building, $room, $phone, $email, $j_id)");

	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Успешно</title>
</head>
<body>

	<table style="margin: 100px auto; text-align: center;" border="0">
		<? if(($q || $z) && ($t || $p) && ($y || $m) && ($x || $w) && $f && $u) {?>
			<tr>
				<td>
					<img src="img/success.jpg" width="300px">
				</td>
			</tr>
			<tr>
				<td>
					<b style="font-size: 22px;">Контакт успешно добавлен</b>
				</td>
			</tr>
		
		<? } else { ?>
			<tr>
				<td>
					<img src="img/error.jpg" width="300px">
				</td>
			</tr>
			<tr>
				<td>
					<b style="font-size: 30px;">Ошибка добавления</b>
				</td>
			</tr>
		<? };
			// sleep(3);
			echo '<meta http-equiv="refresh" content="2;URL=/index.php">';
		?>
	</table>

</body>
</html>