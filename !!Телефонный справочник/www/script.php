<?php
	include "model.php";
	startup();


	// echo "string";
	if(isset($_POST['word']))
		$word = '\'%'.$_POST['word'].'%\'';
		// return;
	// $q = mysql_query("SELECT * FROM surname WHERE surname LIKE $word");
	// $q = mysql_query("SELECT surname.s_id AS s_id, surname.surname FROM main INNER JOIN surname ON main.s_id = surname.s_id");
	// $q = mysql_query("SELECT main.s_id AS surname, main.n_id AS name, main.p_id AS patronymic, main.st_id AS street, main.house, main.building, main.room, main.phone FROM main INNER JOIN surname, name, patronymic, street ON surname.s_id = main.s_id, name.n_id = main.n_id, patronymic.p_id = main.p_id, street.st_id = main.st_id");

	
	$q = mysql_query("SELECT main.id, surname.surname, name.name, patronymic.patronymic, main.sex, street.street, main.house, main.building, main.room, main.phone, main.email, job.place_job, job.position FROM main INNER JOIN surname ON main.s_id = surname.s_id JOIN name ON main.n_id = name.n_id JOIN patronymic ON main.p_id = patronymic.p_id JOIN street ON main.st_id = street.st_id JOIN job ON main.j_id = job.j_id WHERE surname.surname LIKE $word OR name.name LIKE $word OR patronymic.patronymic LIKE $word OR street.street LIKE $word OR main.house LIKE $word OR main.building LIKE $word OR main.room LIKE $word OR main.phone LIKE $word OR main.sex LIKE $word OR main.email LIKE $word OR job.place_job LIKE $word OR job.position LIKE $word");
	// echo $q;
	$word = mb_strtolower($_POST['word']);
	$str = "<b style=\"color: blue;\">".$word."</b>";

	echo "<tr style=\"font-weight: bold;\"><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Пол</td><td>Улица</td><td>Дом</td><td>Корпус</td><td>Квартира</td><td>Номер телефона</td><td>email</td><td>Место работы</td><td>Должность</td></tr>";
	while ($row = mysql_fetch_assoc($q)) {
		$row['surname'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['surname'])));
		$row['name'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['name'])));
		$row['patronymic'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['patronymic'])));
		$row['street'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['street'])));
		$row['email'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['email'])));
		$row['place_job'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['place_job'])));
		$row['position'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['position'])));


		$row['house'] = str_replace($word, $str, $row['house']);
		$row['building'] = str_replace($word, $str, $row['building']);
		$row['room'] = str_replace($word, $str, $row['room']);
		$row['phone'] = str_replace($word, $str, $row['phone']);

		$row['sex'] = ($row['sex'] == 0) ? "Мужской" : "Женский";
		$row['sex'] = mb_ucfirst(str_replace($word, $str, mb_strtolower($row['sex'])));

		echo "<tr tr_search_id=\"".$row['id']."\" class=\"tr_search_table\" onclick='view_panel(\"".$row['id']."\")'><td>".$row['surname']."</td><td>".$row['name']."</td><td>".$row['patronymic']."</td><td>".$row['sex']."</td><td>".$row['street']."</td><td>".$row['house']."</td><td>".$row['building']."</td><td>".$row['room']."</td><td>".$row['phone']."</td><td>".$row['email']."</td><td>".$row['place_job']."</td><td>".$row['position']."</td></tr>";
	    // echo "<tr><td>".$row['surname']."</td><td>".$row['name']."</td><td>".$row['patronymic']."</td><td>".$row['sex']."</td><td>".$row['street']."</td><td>".$row['house']."</td><td>".$row['building']."</td><td>".$row['room']."</td><td>".$row['phone']."</td><td>".$row['email']."</td><td>".$row['place_job']."</td><td>".$row['position']."</td></tr>";
	};


	function mb_ucfirst($str, $encoding='UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
			   mb_substr($str, 1, mb_strlen($str), $encoding);


		// if (!(strpos($str, "<b style=") === false))
		// return $str;
		if (strpos($str, "<b style=") == 0)
			$str = mb_substr($str, 0, 24, $encoding).mb_strtoupper(mb_substr($str, 24, 1, $encoding), $encoding).
			   mb_substr($str, 25, mb_strlen($str), $encoding);


		return $str;
	}

?>