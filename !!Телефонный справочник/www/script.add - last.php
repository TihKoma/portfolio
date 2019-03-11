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


	$z = mysql_query("INSERT INTO surname (surname) VALUES ($surname)");

	$id = '\''.mysql_insert_id().'\'';

	$r = mysql_query("INSERT INTO name (n_id, name) VALUES ($id, $name)");
	$t = mysql_query("INSERT INTO patronymic (p_id, patronymic) VALUES ($id, $patronymic)");
	$y = mysql_query("INSERT INTO street (st_id, street) VALUES ($id, $street)");
	$x = mysql_query("INSERT INTO job (j_id, place_job, position) VALUES ($id, $place_job, $position)");
	$q = mysql_query("INSERT INTO main (id, s_id, n_id, p_id, sex, st_id, house, building, room, phone, email, j_id) VALUES ($id, $id, $id, $id, $sex, $id, $house, $building, $room, $phone, $email, $id)");

	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Успешно</title>
</head>
<body>

	<table style="margin: 100px auto; text-align: center;" border="0">
		<? if($q && $z && $r && $t && $y && $x) {?>
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
			sleep(3);
			echo '<meta http-equiv="refresh" content="0;URL=/index.php">';
		?>
	</table>

</body>
</html>