window.onload = function(){
	// director = $('#surname_director').val()+' '+$('#name_director').val()+' '+$('#middle_name_director').val();
	var count_students = 0;
	var count_teachers = 0;
	$('#save').click(function(){
		count_students = $('#count_students').val();
		count_teachers = $('#count_teachers').val();
		if (($('#full_name').val() == '') || ($('#addres').val() == '') || ($('#index').val() == '') || ($('#email').val() == '')) {
			alert('Данные не введены');
			return;
		};
		$.post(
			'register.php',
			{
				full_name: $('#full_name').val(), 
				addres: $('#addres').val(),
				index: $('#index').val(),
				email: $('#email').val(),
				phone_number: $('#phone_number').val(),
				director: $('#surname_director').val()+' '+$('#name_director').val()+' '+$('#middle_name_director').val(),
				count_teachers: count_teachers,
				count_students: count_students
			}, function(a){
				id_school = a;
				// console.log(id_school);
			}
		);
	//Таблица для заполнения данных об учителях
		$('#content').html('<h2 align="center">Регистрация новой школы</h2><br><h3 align="center">Этап <b>2</b></h3><table id="register" border="1"><tr><td colspan="4" class="column1"><b>Заполнение данных об учителях</b></td></tr></table>');
		for (var i = 1; i <= count_teachers; i++) {
			$('#register').append('<tr><td rowspan="3" class="number">#'+i+'</td><td class="fio"><input type="text" id="surname_teacher_'+i+'" class="fio_text" placeholder="Фамилия"></td><td class="information" id="form_subject_'+i+'">Предмет:<select id="subject_'+i+'"><option>Мировая художественная культура (МХК)</option><option>Астрономия</option><option>Экология</option><option>Философия</option><option>Правоведение</option><option>Основы экономики</option><option>Естествознание</option><option>Химия</option><option>Физика</option><option>Геометрия</option><option>Алгебра</option><option>Черчение</option><option>Обществознание</option><option>Информатика</option><option>Биология</option><option>География</option><option>Технология</option><option>ОБЖ</option><option>Литература</option><option>История</option><option>Краеведение</option><option>Граждановедение</option><option>Иностранный язык</option><option>Физкультура</option><option>Русский язык</option><option>Изобразительное искусство</option><option>Музыка</option><option>Математика</option></select><a id="another_'+i+'" class="another_subject">(Другой)</a></td><td rowspan="3" id="classes"><p align="center" class="text_classes">Классы, где преподает учитель</p><select class="select" id="select_'+i+'_number_1"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select><div class="add" id="'+i+'" count="1"><img src="img/add.png" height="20px"></div></td></tr><tr><td class="fio"><input type="text" id="name_teacher_'+i+'" class="fio_text" placeholder="Имя"></td><td class="information">Телефон:<input type="text" id="phone_number_teacher_'+i+'" class="subject"></td></tr><tr><td class="fio"><input type="text" id="middle_name_teacher_'+i+'" class="fio_text" placeholder="Отчество"></td><td class="information">email:<input type="text" id="email_teacher_'+i+'" class="subject"></td></tr>');
			$('#content').append('<script type="text/javascript">$(\'#another_'+i+'\').mousedown(function(){$(\'#form_subject_'+i+'\').html(\'Предмет: <input type="text" id="subject_'+i+'" class="subject">\');});</script>');
		};

	//Таблица для заполнения данных об учениках и их родителях
		$('#content').append('<table id="table_students" border="1"><tr><td colspan="6" class="column1"><b>Заполнение данных об учениках</b></td></tr></table>');
		for (var i = 1; i <= count_students; i++) {
			$('#table_students').append('<tr><td rowspan="3" class="number">#'+i+'</td><td class="fio"><input type="text" id="surname_student_'+i+'" class="fio_text" placeholder="Фамилия"></td><td class="information" id="form_subject" colspan="2">Класс №<select id="class_'+i+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select></td><td colspan="2"><p align="center">Родители</p></td></tr><tr><td class="fio"><input type="text" id="name_student_'+i+'" class="fio_text" placeholder="Имя"></td><td class="information" colspan="2"><input type="text" id="phone_number_student_'+i+'" class="subject" placeholder="Телефон"></td><td rowspan="2"><input type="text" id="fio_parent1_'+i+'" class="fio_parents" placeholder="Ф.И.О"></td><td rowspan="2"><input type="text" id="fio_parent2_'+i+'" class="fio_parents" placeholder="Ф.И.О"></td></tr><tr><td class="fio"><input type="text" id="middle_name_student_'+i+'" class="fio_text" placeholder="Отчество"></td><td class="information" colspan="2"><input type="text" id="address_student_'+i+'" class="subject" placeholder="Домашний адрес"></td></tr>');
		};
	//Добавление нового select'а
		$('.add').click(function(){
			var count = +$(this).attr('count');
			var id = $(this).attr('id');
			// console.log(id);
			if (count >= 11) return;
			count += 1;
			$(this).before('<select class="select" id="select_'+id+'_number_'+count+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select>');
			$(this).attr('count', count);
			if (count == 11) $(this).empty();	
		});

		$('#content').css('border','0px');
		$('#content').css('margin-top','-20px');
		// $('#content').css('height','100px');
		$('#register').css('width','1110px');

		$('#content').append('<div id="save2">Дальше</div>');

		// $('#content').append('<qwe id="id_teachers"></qwe>');
		$('#save2').click(function(){
			// console.log(id_school);
		//Обработка данных об учителях
			var fio_teachers = new Array();				// ФИО учителей
			var subject = new Array();					// Предмет
			var phone_number_teachers = new Array();	// Номера телефонов
			var email_teachers = new Array();			// email учителей
			var login_teachers = new Array();			// Массив с логинами учителей
			var password_teachers = new Array();		// Массив с паролями учителей
			var classes = new Array();
			var count;
			var who = 'teacher';
			var id = new Array();
			for (var i = 1; i <= count_teachers; i++) {
				fio_teachers[i] = $('#surname_teacher_'+i).val() + ' ' + $('#name_teacher_'+i).val() + ' ' + $('#middle_name_teacher_'+i).val();
				subject[i] = $('#subject_'+i).val();
				phone_number_teachers[i] = $('#phone_number_teacher_'+i).val();
				email_teachers[i] = $('#email_teacher_'+i).val();
				login_teachers[i] = generationLogin($('#surname_teacher_'+i).val(), $('#name_teacher_'+i).val());
				password_teachers[i] = generationPassword();
			//Обработка и составление строки с номерами классов учителя
				count = $('#'+i).attr('count');
				classes[i] = '';
				for (var j = 1; j <= count; j++) {
					classes[i] = classes[i] + $('#select_'+i+'_number_'+j).val()+'^';
				};
				classes[i] = classes[i].substring(0, classes[i].length - 1);
				// console.log(classes[i]);
			//Отправка данных на сервер
				$.post(
					'register.php',
					{
						who: who,
						fio: fio_teachers[i],
						id_school: id_school,
						subject: subject[i],
						phone_number: phone_number_teachers[i],
						email: email_teachers[i],
						login: login_teachers[i],
						password: password_teachers[i],
						classes: classes[i]
					}, function(a){
						console.log(a);
					}
				);
			};

		//Обработка данных об учениках
			var fio_students = new Array();
			var class_number = new Array();
			var phone_number_students = new Array();
			var address_students = new Array();
			var login_students = new Array();
			var password_students = new Array();
			var parent1 = new Array();
			var parent2 = new Array();
			var login_parent1 = new Array();
			var login_parent2 = new Array();
			var password_parent1 = new Array();
			var password_parent2 = new Array();
			who = 'student';
			for (var i = 1; i <= count_students; i++) {
				fio_students[i] = $('#surname_student_'+i).val() + ' ' + $('#name_student_'+i).val() + ' ' + $('#middle_name_student_'+i).val();
				class_number[i] = $('#class_'+i).val();
				phone_number_students[i] = $('#phone_number_student_'+i).val();
				address_students[i] = $('#address_student_'+i).val();
				login_students[i] = generationLogin($('#surname_student_'+i).val(), $('#name_student_'+i).val());
				password_students[i] = generationPassword();

				parent1[i] = $('#fio_parent1_'+i).val();
				parent2[i] = $('#fio_parent2_'+i).val();
				login_parent1[i] = generationLogin(parent1[i].split(' ')[0], parent1[i].split(' ')[1]);
				login_parent2[i] = generationLogin(parent2[i].split(' ')[0], parent2[i].split(' ')[1]);
				password_parent1[i] = generationPassword();
				password_parent2[i] = generationPassword();

				$.post(
					'register.php',
					{
						who: who,
						fio: fio_students[i],
						id_school: id_school,
						class_number: class_number[i],
						phone_number: phone_number_students[i],
						address: address_students[i],
						login: login_students[i],
						password: password_students[i],
						parent1: parent1[i],
						parent2: parent2[i],
						login_parent1: login_parent1[i],
						login_parent2: login_parent2[i],
						password_parent1: password_parent1[i],
						password_parent2: password_parent2[i],
					}, function(a){
						console.log(a);
					}
				);
			};

			login_admin = 'admin' + id_school;
			password_admin = generationPassword();
			who = 'admin';
			$.post(
				'register.php',
				{
					who: who,
					login: login_admin,
					password_admin: password_admin,
					id_school: id_school
				}
			);
		
			$('#content').html('<h2 align="center">Регистрация успешно завершена!</h2></br><h4 align="center">Данные для входа в портал школы</h4><table border="1" id="accounts"><tr><td colspan="3"><h4 align="center" style="height: 10px;"><i>Администратор</i></h4></td></tr><tr><td>Администратор</td><td>'+login_admin+'</td><td>'+password_admin+'</td></tr><tr><td colspan="3"><h4 align="center" style="height: 10px;"><i>Учителя</i></h4></td></tr><tr><td>ФИО</td><td>Логин</td><td>Пароль</td></tr></table>');
			for (var i = 1; i <= count_teachers; i++) {
				$('#accounts').append('<tr><td>'+fio_teachers[i]+'</td><td>'+login_teachers[i].replace('\\','')+'</td><td>'+password_teachers[i]+'</td></tr>');
			};

			$('#accounts').append('<tr><td colspan="3"><h4 align="center" style="height: 10px;"><i>Ученики</i></h4></td></tr><tr><td>ФИО</td><td>Логин</td><td>Пароль</td></tr>');
			for (var i = 1; i <= count_students; i++) {
				$('#accounts').append('<tr><td>'+fio_students[i]+'</td><td>'+login_students[i].replace('\\','')+'</td><td>'+password_students[i]+'</td></tr>');
			};

			$('#accounts').append('<tr><td colspan="3"><h4 align="center" style="height: 10px;"><i>Родители</i></h4></td></tr><tr><td>ФИО</td><td>Логин</td><td>Пароль</td></tr>');
			for (var i = 1; i <= count_students; i++) {
				$('#accounts').append('<tr><td>'+parent1[i]+'</td><td>'+login_parent1[i].replace('\\','')+'</td><td>'+password_parent1[i]+'</td></tr>');
				$('#accounts').append('<tr><td>'+parent2[i]+'</td><td>'+login_parent2[i].replace('\\','')+'</td><td>'+password_parent2[i]+'</td></tr>');
			};

			$('#content').append('<div id="fill_schedule">Перейти к заполнению расписания</div>');
			$('#fill_schedule').click(function(){
				document.location.href = "schedule/schedule1.html";
			});
		});

	});

	

	// $('#another').mousedown(function(){
	// 	$('#form_subject').html('Предмет: <input type="text" id="subject_" class="subject">');
	// 	// $('#back').css('cursor','pointer');
	// 	// $('#back').mousedown(change());
	// });

	// function change(){
	// 	// alert('qwerty');
	// 	$('#form_subject').html('Предмет:<select><option>Мировая художественная культура (МХК)</option><option>Астрономия</option><option>Экология</option><option>Философия</option><option>Правоведение</option><option>Основы экономики</option><option>Естествознание</option><option>Химия</option><option>Физика</option><option>Геометрия</option><option>Алгебра</option><option>Черчение</option><option>Обществознание</option><option>Информатика</option><option>Биология</option><option>География</option><option>Технология</option><option>ОБЖ</option><option>Литература</option><option>История</option><option>Краеведение</option><option>Граждановедение</option><option>Иностранный язык</option><option>Физкультура</option><option>Русский язык</option><option>Изобразительное искусство</option><option>Музыка</option><option>Математика</option></select> <a id="another">(Указать другой)</a>');
	// }
	
}





function translit(str){
	var znak = '\\'+'\'';
	var arr={'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ж':'g', 'з':'z', 'и':'i', 'й':'y', 'к':'k', 'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 'ф':'f', 'ы':'i', 'э':'e', 'А':'A', 'Б':'B', 'В':'V', 'Г':'G', 'Д':'D', 'Е':'E', 'Ж':'G', 'З':'Z', 'И':'I', 'Й':'Y', 'К':'K', 'Л':'L', 'М':'M', 'Н':'N', 'О':'O', 'П':'P', 'Р':'R', 'С':'S', 'Т':'T', 'У':'U', 'Ф':'F', 'Ы':'I', 'Э':'E', 'ё':'yo', 'х':'h', 'ц':'ts', 'ч':'ch', 'ш':'sh', 'щ':'shch', 'ъ':'', 'ь': znak, 'ю':'yu', 'я':'ya', 'Ё':'YO', 'Х':'H', 'Ц':'TS', 'Ч':'CH', 'Ш':'SH', 'Щ':'SHCH', 'Ъ':'', 'Ь':'','Ю':'YU', 'Я':'YA'};
	var replacer=function(a){return arr[a]||a};
	return str.replace(/[А-яёЁ]/g,replacer);
};

function generationLogin(surname, name){
	login = translit(name)+ translit(surname).substr(0,2) + Math.round(Math.random()*1000);
	return login;
};

function generationPassword() {
    var password       = '';
    var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
    var max_position = words.length - 1;
    for( i = 0; i < 10; ++i ) {
        position = Math.floor ( Math.random() * max_position );
        password = password + words.substring(position, position + 1);
    }
    return password;
};
