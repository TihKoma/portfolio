<?php
	include "model.php";
	startup();


	if(isset($_POST['id']) && ($_POST['action'] == "get_record")){
		$id = '\''.$_POST['id'].'\'';


		$q = mysql_query("SELECT main.id, surname.surname, name.name, patronymic.patronymic, main.sex, street.street, main.house, main.building, main.room, main.phone, main.email, job.place_job, job.position FROM main INNER JOIN surname ON main.s_id = surname.s_id JOIN name ON main.n_id = name.n_id JOIN patronymic ON main.p_id = patronymic.p_id JOIN street ON main.st_id = street.st_id JOIN job ON main.j_id = job.j_id WHERE main.id = $id");


		echo "<tr style=\"font-weight: bold;\"><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Пол</td><td>Улица</td><td>Дом</td><td>Корпус</td><td>Квартира</td><td>Номер телефона</td><td>email</td><td>Место работы</td><td>Должность</td></tr>";
		while ($row = mysql_fetch_assoc($q)) {
			$row['sex'] = ($row['sex'] == 0) ? "<option selected>Мужской</option><option>Женский</option>" : "<option>Мужской</option><option selected>Женский</option>";
			echo "<tr id='".$row['id']."'><td><input type='text' id='surname' value='".$row['surname']."'></td><td><input type='text' id='name' value='".$row['name']."'></td><td><input type='text' id='patronymic' value='".$row['patronymic']."'></td><td><select id='sex'>".$row['sex']."</select></td><td><input type='text' id='street' value='".$row['street']."'></td><td><input type='text' id='house' value='".$row['house']."'></td><td><input type='text' id='building' value='".$row['building']."'></td><td><input type='text' id='room' value='".$row['room']."'></td><td><input type='text' id='phone' value='".$row['phone']."'></td><td><input type='text' id='email' value='".$row['email']."'></td><td><input type='text' id='place_job' value='".$row['place_job']."'></td><td><input type='text' id='position' value='".$row['position']."'></td></tr>";
		};
	};



	if(isset($_POST['id']) && ($_POST['action'] == "save_change")){
		$id = '\''.$_POST['id'].'\'';
		$surname = '\''.$_POST['surname'].'\'';
		$name = '\''.$_POST['name'].'\'';
		$patronymic = '\''.$_POST['patronymic'].'\'';
		$sex = '\''.$_POST['sex'].'\'';
		$street = '\''.$_POST['street'].'\'';
		$house = $_POST['house'];
		$building = $_POST['building'];
		$room = $_POST['room'];
		$phone = '\''.$_POST['phone'].'\'';
		$email = '\''.$_POST['email'].'\'';
		$place_job = '\''.$_POST['place_job'].'\'';
		$position = '\''.$_POST['position'].'\'';

		// $q = mysql_query("SELECT main.id, surname.surname, name.name, patronymic.patronymic, main.sex, street.street, main.house, main.building, main.room, main.phone, main.email, job.place_job, job.position FROM main INNER JOIN surname ON main.s_id = surname.s_id JOIN name ON main.n_id = name.n_id JOIN patronymic ON main.p_id = patronymic.p_id JOIN street ON main.st_id = street.st_id JOIN job ON main.j_id = job.j_id WHERE main.id = $id");

		$q = mysql_query("UPDATE main SET sex = $sex, house = $house, building = $building, room = $room, phone = $phone, email = $email WHERE id = $id");

		// $z = mysql_query("UPDATE surname SET surname = $surname WHERE s_id = $id");
		$q = update_surname($id, $surname);
		$r = update_name($id, $name);
		$t = update_patronymic($id, $patronymic);
		$y = update_street($id, $street);

		$x = update_job($id, $place_job, $position);
		// $r = mysql_query("UPDATE name SET name = $name WHERE n_id = $id");
		// $t = mysql_query("UPDATE patronymic SET patronymic = $patronymic WHERE p_id = $id");
		// $y = mysql_query("UPDATE street SET street = $street WHERE st_id = $id");

		if(!$q || !$r || !$t || !$y || !$x)
			echo 0;
		else
			echo 1;
	};


	if(isset($_POST['id']) && ($_POST['action'] == "delete")){
		$id = '\''.$_POST['id'].'\'';

		$q = mysql_query("DELETE FROM main WHERE id = $id");

		// $z = mysql_query("DELETE FROM surname WHERE s_id = $id");
		// $r = mysql_query("DELETE FROM name WHERE n_id = $id");
		// $t = mysql_query("DELETE FROM patronymic WHERE p_id = $id");
		// $y = mysql_query("DELETE FROM street WHERE st_id = $id");
		$x = mysql_query("DELETE FROM job WHERE j_id = $id");

		if($q && $x)
			echo 0;
		else
			echo 1;;
	};



	function update_surname($id, $surname) {
		$q = mysql_query("SELECT * FROM surname WHERE surname = $surname");

		if (mysql_num_rows($q)>0){
			//забираем уже существующее значение
			while ($row = mysql_fetch_assoc($q))
				$s_id = '\''.$row['s_id'].'\''; //surname id
		} else {
			//добавляем новое
			$z = mysql_query("INSERT INTO surname (surname) VALUES ($surname)");
			$s_id = '\''.mysql_insert_id().'\'';
		};

		$q = mysql_query("UPDATE main SET s_id = $s_id WHERE id = $id");

		if ($q)
			return 0;
		return 1;
	};

	function update_name($id, $name) {
		$q = mysql_query("SELECT * FROM name WHERE name = $name");

		if (mysql_num_rows($q)>0){
			//забираем уже существующее значение
			while ($row = mysql_fetch_assoc($q))
				$n_id = '\''.$row['n_id'].'\''; //name id
		} else {
			//добавляем новое
			$z = mysql_query("INSERT INTO name (name) VALUES ($name)");
			$n_id = '\''.mysql_insert_id().'\'';
		};

		$q = mysql_query("UPDATE main SET n_id = $n_id WHERE id = $id");

		if ($q)
			return 0;
		return 1;
	};

	function update_patronymic($id, $patronymic) {
		$q = mysql_query("SELECT * FROM patronymic WHERE patronymic = $patronymic");

		if (mysql_num_rows($q)>0){
			//забираем уже существующее значение
			while ($row = mysql_fetch_assoc($q))
				$p_id = '\''.$row['p_id'].'\''; //patronymic id
		} else {
			//добавляем новое
			$z = mysql_query("INSERT INTO patronymic (patronymic) VALUES ($patronymic)");
			$p_id = '\''.mysql_insert_id().'\'';
		};

		$q = mysql_query("UPDATE main SET p_id = $p_id WHERE id = $id");

		if ($q)
			return 0;
		return 1;
	};

	function update_street($id, $street) {
		$q = mysql_query("SELECT * FROM street WHERE street = $street");

		if (mysql_num_rows($q)>0){
			//забираем уже существующее значение
			while ($row = mysql_fetch_assoc($q))
				$st_id = '\''.$row['st_id'].'\''; //street id
		} else {
			//добавляем новое
			$z = mysql_query("INSERT INTO street (street) VALUES ($street)");
			$st_id = '\''.mysql_insert_id().'\'';
		};

		$q = mysql_query("UPDATE main SET st_id = $st_id WHERE id = $id");

		if ($q)
			return 0;
		return 1;
	};


	function update_job($id, $place_job, $position) {
		$q = mysql_query("SELECT * FROM main WHERE id = $id");

		while ($row = mysql_fetch_assoc($q))
			$j_id = '\''.$row['j_id'].'\''; //job id

		$x = mysql_query("UPDATE job SET place_job = $place_job, position = $position WHERE j_id = $j_id");

		if ($x)
			return 0;
		return 1;
	};


?>