<?php
	header("Content-type: text/html; charset=utf-8");
	include('model.php');
	startup();

//Получение данных о пользователе, школе и пр.
	if (isset($_POST['action']) && $_POST['action'] == 'get_information') {
		if (isset($_COOKIE['login'])) {
			if ($_COOKIE['priv'] != 1) {
				echo "error";
				return;
			};
			$login = $_COOKIE['login'];
			$login = '\''.str_replace('\'', '\\\'', $login).'\'';
			// $password = $_COOKIE['password'];
			// $priv = $_COOKIE['priv'];
			// echo "$login".'%'."$priv";
			session_start();
			$name = $_SESSION['name'];
			$id_school = '\''.$_COOKIE['id_school'].'\'';
			// echo "$login".' '."$id_school";
			$res = mysql_query("SELECT * FROM teachers WHERE login = $login AND id_school = $id_school");
			if ($res != null) {
				while($row = mysql_fetch_assoc($res)) {
					$subject = $row['subject'];
					$classes = $row['classes'];
				};
			};
			$_SESSION['subject'] = $subject;
			$q = mysql_query("SELECT * FROM about_school WHERE id = $id_school");
			if ($q != null) {
				while($row = mysql_fetch_assoc($q)) {
					$full_name = $row['full_name'];
					$address = $row['addres'];
					$index = $row['ind'];
					$email = $row['email'];
					$phone = $row['phone_number'];
					$director = $row['director'];
					$count_teachers = $row['count_teachers'];
					$count_students = $row['count_students'];
				};
			};

			echo "$name".'%'."$id_school".'%'."$subject".'%'."$classes".'%'."$full_name".'%'."$address".'%'."$index".'%'."$email".'%'."$phone".'%'."$director".'%'."$count_teachers".'%'."$count_students";
		};
	};
	

	if (isset($_POST['action']) && $_POST['action'] == 'set_class') {
		$class = $_POST['number_class'];
		session_start();
		$_SESSION['class'] = $class;
		echo $_SESSION['class'];
	};

	if (isset($_POST['action']) && $_POST['action'] == 'get_schedule_and_students') {
		session_start();
		$class = $_SESSION['class'];
		$id_school = $_SESSION['id_school'];
	//Получение ФИО и id учеников
		$q = mysql_query("SELECT * FROM students WHERE class = $class AND id_school = $id_school");
		while($row = mysql_fetch_assoc($q)) {
			$fio .= $row['FIO'].'%';//ФИО учеников
			$id_students .= $row['id'].'^';// id учеников
		};
		$fio = substr($fio, 0, -1); //удаление лишнего '%'
		$id_students = substr($id_students, 0, -1); //удаление лишнего '^'
	//Получение расписания
		$class = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';
		$q = mysql_query("SELECT * FROM schedule WHERE id_school = $id_school AND class = $class");
		while($row = mysql_fetch_assoc($q)) {
			$monday = $row['monday'];
			$tuesday = $row['tuesday'];
			$wednesday = $row['wednesday'];
			$thursday = $row['thursday'];
			$friday = $row['friday'];
			$saturday = $row['saturday'];
		};
		echo "$fio".'&'."$id_students".'&'.$_SESSION['class'].'$'."$monday".'$'."$tuesday".'$'."$wednesday".'$'."$thursday".'$'."$friday".'$'."$saturday".'$'.$_SESSION['subject'];
	};


	if (isset($_POST['action']) && $_POST['action'] == 'write_marks_in_database') {
		$id_students = '\''.$_POST['id_students'].'\'';
		$date = '\''.$_POST['date'].'\'';
		$mark = '\''.$_POST['mark'].'\'';
		// echo "$id_students".' '."$date".' '."$mark";
		session_start();
		$class = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';
		$subject = '\''.$_SESSION['subject'].'\'';
		// echo "$class".' '."$id_school";
		$q = mysql_query("INSERT INTO marks (mark, date, subject, id_student, class, id_school)
			VALUES ($mark, $date, $subject, $id_students, $class, $id_school)");
		echo "$q";
	};


	if (isset($_POST['action']) && $_POST['action'] == 'get_mark') {
		// echo "5";
		$id_student = '\''.$_POST['id_student'].'\'';
		$date = '\''.$_POST['date'].'\'';
		session_start();
		$class = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';

		$q = mysql_query("SELECT * FROM marks WHERE id_student = $id_student AND date = $date AND class = $class AND id_school = $id_school");
		while($row = mysql_fetch_assoc($q)) {
			$mark = $row['mark'];
		};
		if ($mark == null) {
			echo "fail";
		} else{
			echo "$mark".'%'.$_POST['id_student'].'%'.$_POST['date'];
			// echo "$mark";
		};
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'write_homework_to_database')) {
		$date = '\''.$_POST['date'].'\'';
		$homework = '\''.$_POST['homework'].'\'';
		$subject = '\''.$_POST['subject'].'\'';
		session_start();
		$class_number = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';
		// echo "$date".' '."$homework".' '."$subject".' '."$class_number".' '."$id_school";
		$q = mysql_query("INSERT INTO homework (date, subject, homework, class_number, id_school) 
			VALUES ($date, $subject, $homework, $class_number, $id_school)");
		echo "$q";
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'get_homework')) {
		$date = '\''.$_POST['date'].'\'';
		session_start();
		$class_number = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';
		$subject = '\''.$_SESSION['subject'].'\'';
		// echo "$date".' '."$subject".' '."$class_number".' '."$id_school";
		$q = mysql_query("SELECT * FROM homework WHERE date = $date AND subject = $subject AND class_number = $class_number AND id_school = $id_school");
		while($row = mysql_fetch_assoc($q)) {
			$homework = $row['homework'];
		};
		echo "$homework";
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'write_comment_in_database')) {
		$date = '\''.$_POST['date'].'\'';
		$fio_student = '\''.$_POST['fio_student'].'\'';
		$comment = '\''.$_POST['comment'].'\'';
		session_start();
		$class_number = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';
		$subject = '\''.$_SESSION['subject'].'\'';
		$name = '\''.$_SESSION['name'].'\'';
		$q = mysql_query("SELECT * FROM students WHERE FIO = $fio_student AND id_school = $id_school AND class = $class_number");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$id_student = '\''.$row['id'].'\'';
			};
		};
		// echo "$date".' '."$fio_student".' '."$comment".' '."$class_number".' '."$id_school".' '."$subject".' '."$name";
		$q = mysql_query("INSERT INTO comments (date, comment, id_student, class, id_school, subject, FIO_teacher)
			VALUES ($date, $comment, $id_student, $class_number, $id_school, $subject, $name)");
		echo "$q";
	};



//==================================Страница ученика=======================================
	if (isset($_POST['action']) && ($_POST['action'] == 'get_information_for_student')) {
		//Если пользователь не авторизован или он не является учеником, то выходим
		if ((!isset($_COOKIE['login'])) || ($_COOKIE['priv'] != 2)) {
			echo "error";
			return;
		};
		$login = $_COOKIE['login'];
		$login = '\''.str_replace('\'', '\\\'', $login).'\'';
		$password = '\''.$_COOKIE['password'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		// echo "$login".' '."$password".' '."$id_school";
		session_start();
		//Если пользователь выыходил, но авторизован, то восстанавливаем данные о нем по кукам
		if ((!isset($_SESSION['name'])) || (!isset($_SESSION['class']))) {
			$q = mysql_query("SELECT * FROM students WHERE login = $login AND password = $password AND id_school = $id_school");
			while($row = mysql_fetch_assoc($q)) {
				$id_student = $row['id'];
				$name = $row['FIO'];
				$class = $row['class'];
			};
			$_SESSION['name'] = $name;
			$_SESSION['class'] = $class;
			$_SESSION['id_student'] = $id_student;
		};

		$q = mysql_query("SELECT * FROM about_school WHERE id = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$full_name = $row['full_name'];
				$address = $row['addres'];
				$index = $row['ind'];
				$email = $row['email'];
				$phone = $row['phone_number'];
				$director = $row['director'];
				$count_teachers = $row['count_teachers'];
				$count_students = $row['count_students'];
			};
		};
		// echo $_SESSION['class'];
		echo $_SESSION['name'].'%'.$_SESSION['class'].'^'."$full_name".'%'."$address".'%'."$index".'%'."$email".'%'."$phone".'%'."$director".'%'."$count_teachers".'%'."$count_students";
	};

	if (isset($_POST['action']) && ($_POST['action'] == 'get_schedule')) {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$class = '\''.$_SESSION['class'].'\'';
		$q = mysql_query("SELECT * FROM schedule WHERE class = $class AND id_school = $id_school");
		while($row = mysql_fetch_assoc($q)) {
			$monday = $row['monday'];
			$tuesday = $row['tuesday'];
			$wednesday = $row['wednesday'];
			$thursday = $row['thursday'];
			$friday = $row['friday'];
			$saturday = $row['saturday'];
		};
		echo "$monday".'%'."$tuesday".'%'."$wednesday".'%'."$thursday".'%'."$friday".'%'."$saturday";
	};

	if (isset($_POST['action']) && ($_POST['action'] == 'get_homework_and_mark_for_diary')) {
		$date = '\''.$_POST['date'].'\'';
		$subject = '\''.$_POST['subject'].'\'';
		session_start();
		$class_number = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$id_student = '\''.$_SESSION['id_student'].'\'';
		// echo "$date".' '."$subject".' '."$class_number".' '."$id_school".' '."$id_student";
		//Достаем ДЗ
		$q = mysql_query("SELECT * FROM homework WHERE date = $date AND subject = $subject AND class_number = $class_number AND id_school = $id_school");
		if ($q != null) {//Проверяем, есть ли ДЗ
			while($row = mysql_fetch_assoc($q)) {
				$homework = $row['homework'];
			};
		} else {
			$homework = 'fail';
		};
		

		$q = mysql_query("SELECT * FROM marks WHERE date = $date AND subject = $subject AND class = $class_number AND id_school = $id_school");
		if ($q != null) {//Проверяем, есть ли оценка
			while($row = mysql_fetch_assoc($q)) {
				$mark = $row['mark'];
			};
		} else {
			$mark = 'fail';
		};
		if ($homework == "") $homework = 'fail';
		if ($mark == "") $mark = 'fail';
		echo "$homework".'&'."$mark";
	};


//==================================Страница родителя=======================================
	if (isset($_POST['action']) && ($_POST['action'] == 'get_information_for_parent')) {
		//Если пользователь не авторизован или он не является родителем, то выходим
		if ((!isset($_COOKIE['login'])) || (!isset($_COOKIE['priv'])) || ($_COOKIE['priv'] != 3)) {
			echo "error";
			return;
		};
		$login = $_COOKIE['login'];
		$login = '\''.str_replace('\'', '\\\'', $login).'\'';
		$password = '\''.$_COOKIE['password'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		// echo "$login".' '."$password".' '."$id_school";
		session_start();
		//Если пользователь выходил, но авторизован, то восстанавливаем данные о нем по кукам
		if ((!isset($_SESSION['name']))) {// || (!isset($_SESSION['name']))
			$q = mysql_query("SELECT * FROM parents WHERE login = $login AND password = $password AND id_school = $id_school");
			if ($q != null) {
				while($row = mysql_fetch_assoc($q)) {
					$name = $row['FIO'];
				};
				$_SESSION['name'] = $name;
			};	
		};
		//Если нет данных о классе и ФИО ребенка, то достаем их из БД
		if ((!isset($_SESSION['class'])) || (!isset($_SESSION['fio_child']))) {
			$q = mysql_query("SELECT * FROM parents WHERE login = $login AND password = $password AND id_school = $id_school");
			if ($q != null) {
				while($row = mysql_fetch_assoc($q)) {
					$id_child = $row['id_child'];
				};
				$_SESSION['id_student'] = $id_child;
				$id_child = '\''.$id_child.'\'';
			};
			//Достаем ФИО и класс из БД по id ученика
			$a = mysql_query("SELECT * FROM students WHERE id = $id_child");
			if ($a != null) {
				while($row = mysql_fetch_assoc($a)) {
					$_SESSION['fio_child'] = $row['FIO'];
					$_SESSION['class'] = $row['class'];
				};
			};
		};
		//Получение данных о школе
		$q = mysql_query("SELECT * FROM about_school WHERE id = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$full_name = $row['full_name'];
				$address = $row['addres'];
				$index = $row['ind'];
				$email = $row['email'];
				$phone = $row['phone_number'];
				$director = $row['director'];
				$count_teachers = $row['count_teachers'];
				$count_students = $row['count_students'];
			};
		};
		// echo $_SESSION['class'];
		echo $_SESSION['name'].'%'.$_SESSION['class'].'%'.$_SESSION['fio_child'].'^'."$full_name".'%'."$address".'%'."$index".'%'."$email".'%'."$phone".'%'."$director".'%'."$count_teachers".'%'."$count_students";
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'get_marks_and_comments')) {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		session_start();
		$class = '\''.$_SESSION['class'].'\'';
		$id_student = '\''.$_SESSION['id_student'].'\'';
		echo $_SESSION['fio_child'].' '.$_SESSION['class'];
		// echo "$id_school".' '."$class".' '."$id_student";
		$q = mysql_query("SELECT * FROM marks WHERE id_student = $id_student AND class = $class AND id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$marks .= $row['mark'].'&';
				$date_marks .= $row['date'].'%';
				$subject .= $row['subject'].'$';
			};
		};
		$marks = substr($marks, 0, -1); //удаление лишнего '&'
		$date_marks = substr($date_marks, 0, -1); //удаление лишнего '%'
		$subject = substr($subject, 0, -1); //удаление лишнего '$'

		$q = mysql_query("SELECT * FROM comments WHERE id_student = $id_student AND class = $class AND id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$date .= $row['date'].'%';
				$comments .= $row['comment'].'&';
				$subjects .= $row['subject'].'$';
				$fio_teachers .= $row['FIO_teacher'].'*';
			};
		};
		$date = substr($date, 0, -1); //удаление лишнего '%'
		$comments = substr($comments, 0, -1); //удаление лишнего '&'
		$subjects = substr($subjects, 0, -1); //удаление лишнего '$'
		$fio_teachers = substr($fio_teachers, 0, -1); //удаление лишнего '*'
		echo "$marks".'^'."$date_marks".'^'."$subject".'@'."$date".'^'."$comments".'^'."$subjects".'^'."$fio_teachers";
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'log_out')) {
		log_out();
	};

//====================================Новости=================================================
	//Добавление новой
	if (isset($_POST['action']) && ($_POST['action'] == 'write_news_in_database')) {
		// echo "string";
		$title = '\''.$_POST['title'].'\'';
		$content = '\''.$_POST['content'].'\'';
		$date = '\''.$_POST['date'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		session_start();
		$author = '\''.$_SESSION['name'].'\'';
		// echo "$title".' '."$content".' '."$date";
		$q = mysql_query("INSERT INTO news (date, title, content, author, id_school)
			VALUES ($date, $title, $content, $author, $id_school)");
		echo "$q";
	};

	//Вывод всех новостей
	if (isset($_POST['action']) && ($_POST['action'] == 'get_news')) {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM news WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$date .= $row['date'].'%';
				$title .= $row['title'].'*';
				$content .= $row['content'].'&';
				$author .= $row['author'].'$';
			};
			$date = substr($date, 0, -1); //удаление лишнего '%'
			$title = substr($title, 0, -1); //удаление лишнего '*'
			$content = substr($content, 0, -1); //удаление лишнего '&'
			$author = substr($author, 0, -1); //удаление лишнего '$'
		};
		echo "$date".'^'."$title".'^'."$content".'^'."$author";
	};

//====================================Форум===================================================
	//Сохранение новой темы
	if (isset($_POST['action']) && ($_POST['action'] == 'write_theme_in_database')) {
		// echo "string";
		$title = '\''.$_POST['title'].'\'';
		$content = '\''.$_POST['content'].'\'';
		$date = '\''.$_POST['date'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		session_start();
		$author = '\''.$_SESSION['name'].'\'';
		// echo "$title".' '."$content".' '."$date";
		$q = mysql_query("INSERT INTO themes (date, title, content, id_school, author)
			VALUES ($date, $title, $content, $id_school, $author)");
		// $id = mysql_insert_id();
		echo "$q";
	};

	//Вывод всех тем
	if (isset($_POST['action']) && ($_POST['action'] == 'get_themes')) {	
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM themes WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$id .= $row['id'].'#';
				$date .= $row['date'].'%';
				$title .= $row['title'].'*';
				$author .= $row['author'].'$';
			};
			$id = substr($id, 0, -1); //удаление лишнего '#'
			$date = substr($date, 0, -1); //удаление лишнего '%'
			$title = substr($title, 0, -1); //удаление лишнего '*'
			$author = substr($author, 0, -1); //удаление лишнего '$'
		};
		echo "$id".'^'."$date".'^'."$title".'^'."$author";
	};

	//Вывод одной темы
	if (isset($_POST['action']) && ($_POST['action'] == 'get_specific_theme')) {	
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$id = '\''.$_POST['id_theme'].'\'';
		$q = mysql_query("SELECT * FROM themes WHERE id = $id");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$date = $row['date'];
				$title = $row['title'];
				$content = $row['content'];
				$author = $row['author'];
			};
			// $date = substr($date, 0, -1); //удаление лишнего '%'
			// $title = substr($title, 0, -1); //удаление лишнего '*'
			// $content = substr($content, 0, -1); //удаление лишнего '&'
			// $author = substr($author, 0, -1); //удаление лишнего '$'
		};
		$q = mysql_query("SELECT * FROM comments_on_themes WHERE id_theme = $id");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$date_comment .= $row['date'].'%';
				$author_comment .= $row['author'].'$';
				$content_comment .= $row['content'].'&';
			};
			$date_comment = substr($date_comment, 0, -1); //удаление лишнего '%'
			$content_comment = substr($content_comment, 0, -1); //удаление лишнего '&'
			$author_comment = substr($author_comment, 0, -1); //удаление лишнего '$'
		};
		// echo "$date_comment".' '."$content_comment".' '."$author_comment";
		echo "$date".'^'."$title".'^'."$content".'^'."$author".'^'."$date_comment".'^'."$content_comment".'^'."$author_comment";
	};

	//Сохранение комментария
	if (isset($_POST['action']) && ($_POST['action'] == 'save_new_comment')) {
		// echo "string";
		$id_theme = '\''.$_POST['id_theme'].'\'';
		$date = '\''.$_POST['date'].'\'';
		$content = '\''.$_POST['content'].'\'';
		session_start();
		$author = '\''.$_SESSION['name'].'\'';
		// echo "$title".' '."$content".' '."$date";
		$q = mysql_query("INSERT INTO comments_on_themes (id_theme, date, author, content)
			VALUES ($id_theme, $date, $author, $content)");
		// $id = mysql_insert_id();
		echo $_SESSION['name'];
	};

//====================================Администратор===========================================
	if (isset($_POST['action']) && ($_POST['action'] == 'get_information_for_admin')) {
		//Если пользователь не авторизован или он не является родителем, то выходим
		if ((!isset($_COOKIE['login'])) || (!isset($_COOKIE['priv'])) || ($_COOKIE['priv'] != 4)) {
			echo "error";
			return;
		};
		$login = '\''.$_COOKIE['login'].'\'';
		$password = '\''.$_COOKIE['password'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		// echo "$login".' '."$password".' '."$id_school";
		session_start();
		//Получение данных о школе
		$q = mysql_query("SELECT * FROM about_school WHERE id = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$full_name = $row['full_name'];
				$address = $row['addres'];
				$index = $row['ind'];
				$email = $row['email'];
				$phone = $row['phone_number'];
				$director = $row['director'];
				$count_teachers = $row['count_teachers'];
				$count_students = $row['count_students'];
			};
		};
		echo "$full_name".'%'."$address".'%'."$index".'%'."$email".'%'."$phone".'%'."$director".'%'."$count_teachers".'%'."$count_students";
	};


	if (isset($_POST['action']) && ($_POST['action'] == 'save_information')) {
		$full_name = '\''.$_POST['name'].'\'';
		$address = '\''.$_POST['address'].'\'';
		$index = '\''.$_POST['index'].'\'';
		$email = '\''.$_POST['email'].'\'';
		$phone = '\''.$_POST['phone'].'\'';
		$director = '\''.$_POST['director'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		// echo "$full_name".' '."$address".' '."$index".' '."$email".' '."$phone".' '."$director".' '."$id_school";
		$q = mysql_query("UPDATE about_school SET full_name = $full_name, addres = $address, ind = $index, email = $email, phone_number = $phone, director = $director WHERE id = $id_school");
		echo "$q";
	};

	if (isset($_POST['action']) && ($_POST['action'] == 'get_schedule_for_admin')) {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$class = '\''.$_POST['class_number'].'\'';
		$weekday = $_POST['weekday'];
		$q = mysql_query("SELECT * FROM schedule WHERE class = $class AND id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				if ($weekday == 'monday') {
					$schedule = $row['monday'];
				} elseif ($weekday == 'tuesday') {
					$schedule = $row['tuesday'];
				} elseif ($weekday == 'wednesday') {
					$schedule = $row['wednesday'];
				} elseif ($weekday == 'thursday') {
					$schedule = $row['thursday'];
				} elseif ($weekday == 'friday') {
					$schedule = $row['friday'];
				} elseif ($weekday == 'saturday') {
					$schedule = $row['saturday'];
				};
			};
		};
		// echo "$monday".'%'."$tuesday".'%'."$wednesday".'%'."$thursday".'%'."$friday".'%'."$saturday";
		echo "$schedule";
	};

	//Изменить расписание
	if (isset($_POST['action']) && ($_POST['action'] == 'update_schedule')) {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$class = '\''.$_POST['class_number'].'\'';
		$weekday = $_POST['weekday'];
		$schedule = '\''.$_POST['schedule'].'\'';
		// echo "$weekday";
		$q = mysql_query("UPDATE schedule SET $weekday = $schedule WHERE id_school = $id_school AND class = $class");
		echo "$q";
	};

	if (isset($_POST['action']) && ($_POST['action'] == 'add_new_student')){
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$class = '\''.$_POST['class_number'].'\'';
		$fio = '\''.$_POST['fio'].'\'';
		$phone = '\''.$_POST['phone'].'\'';
		$address = '\''.$_POST['address'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$a = mysql_query("INSERT INTO students (FIO, id_school, class, phone_number, address, login, password)
			VALUES ($fio, $id_school, $class, $phone, $address, $login, $password)");
		echo "$a";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'add_new_teacher') {
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$subject = '\''.$_POST['subject'].'\'';
		$classes = '\''.$_POST['classes'].'\'';
		$phone_number = '\''.$_POST['phone'].'\'';
		$email = '\''.$_POST['email'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$a = mysql_query("INSERT INTO teachers (FIO, id_school, subject, classes, phone_number, email, login, password)
			VALUES ($fio, $id_school, $subject, $classes, $phone_number, $email, $login, $password)");
		echo $a;
	};

	if (isset($_POST['action']) && $_POST['action'] == 'get_child') {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM students WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$fio .= $row['FIO'].'%';
			};	
			$fio = substr($fio, 0, -1); //удаление лишнего '%'
		};
		echo "$fio";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'add_new_parent') {
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$child = '\''.$_POST['child'].'\'';
		$login = '\''.$_POST['login'].'\'';
		$password = '\''.md5($_POST['password']).'\'';
		$q = mysql_query("SELECT * FROM students WHERE id_school = $id_school AND FIO = $child");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$id = $row['id'];
			};	
		};
		$q = mysql_query("INSERT INTO parents (FIO, id_child, id_school, login, password)
			VALUES ($fio, $id, $id_school, $login, $password)");
		echo "$q";

	};

	if (isset($_POST['action']) && $_POST['action'] == 'get_students') {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM students WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$fio .= $row['FIO'].'%';
			};	
			$fio = substr($fio, 0, -1); //удаление лишнего '%'
		};
		echo "$fio";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'get_teachers') {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM teachers WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$fio .= $row['FIO'].'%';
			};	
			$fio = substr($fio, 0, -1); //удаление лишнего '%'
		};
		echo "$fio";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'get_parents') {
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("SELECT * FROM parents WHERE id_school = $id_school");
		if ($q != null) {
			while($row = mysql_fetch_assoc($q)) {
				$fio .= $row['FIO'].'%';
			};	
			$fio = substr($fio, 0, -1); //удаление лишнего '%'
		};
		echo "$fio";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'delete_student') {
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("DELETE FROM students WHERE id_school = $id_school AND FIO = $fio");
		echo "$q";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'delete_teachers') {
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("DELETE FROM teachers WHERE id_school = $id_school AND FIO = $fio");
		echo "$q";
	};

	if (isset($_POST['action']) && $_POST['action'] == 'delete_parent') {
		$fio = '\''.$_POST['fio'].'\'';
		$id_school = '\''.$_COOKIE['id_school'].'\'';
		$q = mysql_query("DELETE FROM parents WHERE id_school = $id_school AND FIO = $fio");
		echo "$q";
	};


	if (isset($_POST['action']) && $_POST['action'] == 'get_marks_for_statistics') {
		session_start();
		$class = '\''.$_SESSION['class'].'\'';
		$id_school = '\''.$_SESSION['id_school'].'\'';

		$q = mysql_query("SELECT * FROM marks WHERE class = $class AND id_school = $id_school");
		while($row = mysql_fetch_assoc($q)) {
			$marks .= $row['mark'].'%';
		};
		$marks = substr($marks, 0, -1); //удаление лишнего '%'
		echo "$marks";
	};

//====================================Выход из системы========================================
function log_out()
{
	setcookie('login', '', time() - 1);
	setcookie('password', '', time() - 1);
	setcookie('priv', '', time() - 1);
	setcookie('id_school', '', time() - 1);
	unset($_COOKIE['login']);
	unset($_COOKIE['password']);
	unset($_COOKIE['priv']);
	unset($_COOKIE['id_school']);
	unset($_SESSION['name']);
	unset($_SESSION['id_school']);
	unset($_SESSION['id_student']);
	unset($_SESSION['subject']);
	unset($_SESSION['class']);
	unset($_SESSION['fio_child']);
	echo "string";
};


?>