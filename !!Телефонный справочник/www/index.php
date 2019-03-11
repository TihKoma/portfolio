<?

	include "model.php";
	startup();
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Выше 3 Мета-теги ** должны прийти в первую очередь в голове; любой другой руководитель контент *после* эти теги -->  
    <title>Телефонный справочник</title>

    <!-- Bootstrap -->  
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">

    <script src="js/script.js"></script>

    <meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />

	    <!-- на jQuery (необходим для Bootstrap - х JavaScript плагины) -->  
    <script src="js/jquery-3.3.1.js"></script>
    <!-- Включают все скомпилированные плагины (ниже), или включать отдельные файлы по мере необходимости -->  
    <script src="js/bootstrap.js"></script>
	<link href="css/jquery-ui.css" rel="stylesheet">
	<script src="js/jquery-ui.js"></script>
    <!-- HTML5 Shim and Respond.js for IE8 support of HTML5 elements and media queries -->  
    <!-- Предупреждение: Respond.js не работает при просмотре страницы через файл:// -->  
    <!--[if lt IE 9]>
 <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script >
 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
 <![endif]-->  
  	</head>
    <body>



	<h1 align="center">
		Телефонный справочник
	</h1>

	<div>

		  <!-- Навигационные вкладки -->  
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#list_contacts" aria-controls="list_contacts" role="tab" data-toggle="tab">Список контактов</a></li>
		    <li role="presentation"><a href="#search" aria-controls="search" role="tab" data-toggle="tab">Поиск</a></li>
		    <li role="presentation"><a href="#add" aria-controls="add" role="tab" data-toggle="tab">Добавить</a></li>
		  </ul>

		  <!-- Вкладки панелей -->  
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="list_contacts">
		    	<div id="div_load"></div>

		    	<table border="1" class="main_list_table" style="margin-top: 50px;">
		    		<tr style="font-weight: bold;">
		    			<td>Фамилия</td>
		    			<td>Имя</td>
		    			<td>Отчество</td>
		    			<td>Пол</td>
		    			<td>Улица</td>
		    			<td>Дом</td>
		    			<td>Корпус</td>
		    			<td>Квартира</td>
		    			<td>Номер телефона</td>
		    			<td>email</td>
		    			<td>Место работы</td>
		    			<td>Должность</td>
		    		</tr>

		    		<?
						$q = mysql_query("SELECT main.id, surname.surname, name.name, patronymic.patronymic, main.sex, street.street, main.house, main.building, main.room, main.phone, main.email, job.place_job, job.position FROM main INNER JOIN surname ON main.s_id = surname.s_id JOIN name ON main.n_id = name.n_id JOIN patronymic ON main.p_id = patronymic.p_id JOIN street ON main.st_id = street.st_id JOIN job ON main.j_id = job.j_id");
						while ($row = mysql_fetch_assoc($q)) {
							$row['sex'] = ($row['sex'] == 0) ? "Мужской" : "Женский";
						    echo "<tr tr_id=\"".$row['id']."\" class=\"tr_list_table\"><td>".$row['surname']."</td><td>".$row['name']."</td><td>".$row['patronymic']."</td><td>".$row['sex']."</td><td>".$row['street']."</td><td>".$row['house']."</td><td>".$row['building']."</td><td>".$row['room']."</td><td>".$row['phone']."</td><td>".$row['email']."</td><td>".$row['place_job']."</td><td>".$row['position']."</td></tr>";
						};
		    		?>

		    		<!-- <tr>
		    			<td>Иванов</td>
		    			<td>Алексей</td>
		    			<td>Артемович</td>
		    			<td>Мужской</td>
		    			<td>Дубосековская</td>
		    			<td>13</td>
		    			<td>0</td>
		    			<td>1509</td>
		    			<td>89200858131</td>
		    			<td>tihkoma@ya.ru</td>
		    			<td>yandex</td>
		    			<td>Ген директор</td>
		    		</tr> -->
		    	</table>
		    </div>
		    <div role="tabpanel" class="tab-pane" id="search">
		    	<form>
		    		<input type="text" id="search_word" placeholder="Слово для поиска" onkeyup="search(event)" AUTOCOMPLETE="off">
		    	</form> <br>

		    	<div id="div_load_search"></div>

		    	<table border="1" class="main_list_table" id="search_table">
		    		<tr style="font-weight: bold;">
		    			<td>Фамилия</td>
		    			<td>Имя</td>
		    			<td>Отчество</td>
		    			<td>Пол</td>
		    			<td>Улица</td>
		    			<td>Дом</td>
		    			<td>Корпус</td>
		    			<td>Квартира</td>
		    			<td>Номер телефона</td>
		    			<td>email</td>
		    			<td>Место работы</td>
		    			<td>Должность</td>
		    		</tr>

		    		<?
						$q = mysql_query("SELECT main.id, surname.surname, name.name, patronymic.patronymic, main.sex, street.street, main.house, main.building, main.room, main.phone, main.email, job.place_job, job.position FROM main INNER JOIN surname ON main.s_id = surname.s_id JOIN name ON main.n_id = name.n_id JOIN patronymic ON main.p_id = patronymic.p_id JOIN street ON main.st_id = street.st_id JOIN job ON main.j_id = job.j_id");
						while ($row = mysql_fetch_assoc($q)) {
							$row['sex'] = ($row['sex'] == 0) ? "Мужской" : "Женский";
						    echo "<tr tr_search_id=\"".$row['id']."\" class=\"tr_search_table\" onclick='view_panel(".$row['id'].")'><td>".$row['surname']."</td><td>".$row['name']."</td><td>".$row['patronymic']."</td><td>".$row['sex']."</td><td>".$row['street']."</td><td>".$row['house']."</td><td>".$row['building']."</td><td>".$row['room']."</td><td>".$row['phone']."</td><td>".$row['email']."</td><td>".$row['place_job']."</td><td>".$row['position']."</td></tr>";
						};
		    		?>
		    	</table>

		    </div>
		    <div role="tabpanel" class="tab-pane" id="add">

		    	<form method="POST" action="script.add.php">
		    		<table border="1" class="main_list_table" id="add_table">
			    		<tr style="font-weight: bold;">
			    			<td>Фамилия</td>
			    			<td>Имя</td>
			    			<td>Отчество</td>
			    			<td>Пол</td>
			    			<td>Улица</td>
			    			<td>Дом</td>
			    			<td>Корпус</td>
			    			<td>Квартира</td>
			    			<td>Номер телефона</td>
			    			<td>email</td>
			    			<td>Место работы</td>
			    			<td>Должность</td>
			    		</tr>
			    		<tr style="font-weight: bold;">
			    			<td>
			    				<input type="text" name="surname" id="surname_add" autocomplete="off" required>
			    			</td>
			    			<td>
			    				<input type="text" name="name" id="name_add" required>
			    			</td>
			    			<td>
			    				<input type="text" name="patronymic" id="patronymic_add" required>
			    			</td>
			    			<td>
			    				<select name="sex" id="sex_add" required>
			    					<option>Мужской</option>
			    					<option>Женский</option>
			    				</select>
			    			</td>
			    			<td>
			    				<input type="text" name="street" id="street_add" required onkeyup="search_street(event)" AUTOCOMPLETE="off">
			    			</td>
			    			<td>
			    				<input type="text" name="house" id="house_add" required>
			    			</td>
			    			<td>
			    				<input type="text" name="building" id="building_add" required>
			    			</td>
			    			<td>
			    				<input type="text" name="room" id="room_add" required>
			    			</td>
			    			<td>
			    				<input type="text" name="phone" id="phone_add" required>
			    			</td>
			    			<td>
			    				<input type="email" name="email" id="email_add" required>
			    			</td>
			    			<td>
			    				<input type="text" name="place_job" id="place_job_add" required onkeyup="search_place_job(event)" AUTOCOMPLETE="off">
			    			</td>
			    			<td>
			    				<input type="text" name="position" id="position_add" required onkeyup="search_position(event)" AUTOCOMPLETE="off">
			    			</td>
			    		</tr>

			    	</table>

			    	<button type="submit" class="btn btn-primary" id="btn_add">Добавить</button>
		    	</form>

		    </div>
		    <div role="tabpanel" class="tab-pane" id="settings">...</div>
		  </div>

	</div>


	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Панель просмотра</h4>
				</div>

				<div class="modal-body">
					<table border="1" class="main_list_table" id="panel_search_table">
			    		<tr style="font-weight: bold;">
			    			<td>Фамилия</td>
			    			<td>Имя</td>
			    			<td>Отчество</td>
			    			<td>Пол</td>
			    			<td>Улица</td>
			    			<td>Дом</td>
			    			<td>Корпус</td>
			    			<td>Квартира</td>
			    			<td>Номер телефона</td>
			    			<td>email</td>
			    			<td>Место работы</td>
			    			<td>Должность</td>
			    		</tr>
			    	</table>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" id="delete">Удалить</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
					<button type="button" class="btn btn-primary" id="save_change">Сохранить изменения</button>
				</div>
			</div>
		</div>
	</div>


	<!-- <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel">Заголовок модального элемента</h3>
		</div>
		<div class="modal-body">
			<p>Некое изящное тело……</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
			<button class="btn btn-primary">Сохранить изменения</button>
		</div>
	</div> -->




  </body>
</html>