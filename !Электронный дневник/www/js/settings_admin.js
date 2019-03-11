window.onload = function() {
	$('#change_information_school').click(function(){
		$('#window').css('top', '253px');
		act = 'get_information_for_admin';
		$.post(
			'script.php',
			{
				action: act
			}, function(a){
				// console.log(a);
				if (a == 'error') display_error();
				school = a.split('%');
				display_information(school[0],school[1],school[2],school[3],school[4],school[5]);
			}
		);
		});
	$('#log_out').click(function(){
		log_out();
	});

	$('#close').click(function(){
		$('#window').css('top', '-1000px');
	});

	$('#close_schedule').click(function(){
		$('#window_schedule').css('top', '-1000px');
		// $('#table_schedule').html('<tr>td>Класс №</td><td><select id="class_number"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select></td></tr><tr><td>День недели</td><td><select id="weekday"><option>Понедельник</option><option>Вторник</option><option>Среда</option><option>Четверг</option><option>Пятница</option><option>Суббота</option></select></td></tr>');
		// $('#window_schedule').append('<div id="next_schedule">Дальше</div>');
	});

	$('#close_add_student').click(function(){
		$('#window_add_student').css('top', '-1000px');
	});

	$('#close_add_teacher').click(function(){
		$('#window_add_teacher').css('top', '-1000px');
	});

	$('#close_add_parent').click(function(){
		$('#window_add_parent').css('top', '-1000px');
	});

	$('#close_del_parent').click(function(){
		$('#window_del_parent').css('top', '-1000px');
	});

	$('#close_del_student').click(function(){
		$('#window_del_student').css('top', '-1000px');
	});

	$('#close_del_teacher').click(function(){
		$('#window_del_teacher').css('top', '-1000px');
	});

	$('#save_information').click(function(){
		save_information();
	});

	$('#change_schedule').click(function(){
		$('#window_schedule').css('top', '253px');
	});

	$('#next_schedule').click(function(){
		class_number = $('#class_number').val();
		weekday = $('#weekday').val();
		if (weekday == 'Понедельник') {
			day = 'monday';
		} else if (weekday == 'Вторник') {
			day = 'tuesday';
		} else if (weekday == 'Среда') {
			day = 'wednesday';
		} else if (weekday == 'Четверг') {
			day = 'thursday';
		} else if (weekday == 'Пятница') {
			day = 'friday';
		} else if (weekday == 'Суббота') {
			day = 'saturday';
		};
		act = 'get_schedule_for_admin';
		$.post(
			'script.php',
			{
				action: act,
				class_number: class_number,
				weekday: day
			}, function(a){
				console.log(a);
				$('#window_schedule').css('width','600px');
				$('#table_schedule').css('width','560px');
				$('#table_schedule').html('<tr><td>Что заменить</td><td>На что заменить</td></tr><tr><td><select id="old_subject"></select></td><td id="subject"><select id="new_subject"><option>Мировая художественная культура (МХК)</option><option>Астрономия</option><option>Экология</option><option>Философия</option><option>Правоведение</option><option>Основы экономики</option><option>Естествознание</option><option>Химия</option><option>Физика</option><option>Геометрия</option><option>Алгебра</option><option>Черчение</option><option>Обществознание</option><option>Информатика</option><option>Биология</option><option>География</option><option>Технология</option><option>ОБЖ</option><option>Литература</option><option>История</option><option>Краеведение</option><option>Граждановедение</option><option>Иностранный язык</option><option>Физкультура</option><option>Русский язык</option><option>Изобразительное искусство</option><option>Музыка</option><option>Математика</option></select><a id="another"">(Другой)</a></td></tr>');
				a = a.split('*');
				for (var i = 0; i <= a.length-1; i++) {
					$('#old_subject').append('<option>'+a[i]+'</option>');
				};
				// $('body').append('');
				$('#another').click(function(){
					$('#subject').html('<input type="text" id="new_subject">');
				});
				d = $('#next_schedule').detach();
				$('#window_schedule').append('<div id="save_schedule">Сохранить<div>');
				$('#save_schedule').click(function(){
					save_schedule(weekday, class_number, a)
				});
			}
		);
	});

	$('.add').click(function(){
		who = $(this).attr('id');
		// alert(who);
		if (who == 'student') {
			$('#window_add_student').css('top', '253px');
		} else if (who == 'teacher') {
			$('#window_add_teacher').css('top', '253px');
		} else if (who == 'parent') {
			$('#window_add_parent').css('top', '253px');
		};
	});

	$('#add_student').click(function(){
		add_student();
	});

	//Добавление нового select'а
		$('.add_class').click(function(){
			count = +$(this).attr('count');
			id = $(this).attr('id');
			// console.log(id);
			if (count >= 11) return;
			count += 1;
			$(this).before('<select class="select" id="select_'+count+'"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select>');
			$(this).attr('count', count);
			if (count == 11) $(this).empty();	
		});
	$('#add_teacher').click(function(){
		add_teacher();
	});

	$('#another_subject').click(function(){
		$('#td_subject_teacher').html('<input type="text" id="subject_teacher">');
	});

	act = 'get_child';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			a = a.split('%');
			for (var i = 0; i <= a.length-1; i++) {
				$('#child').append('<option>'+a[i]+'</option>');
			};
		}
	);

	$('#add_parent').click(function(){
		add_parent();
	});


	$('.del').click(function(){
		who = $(this).attr('id');
		// alert(who);
		if (who == 'student1') {
			$('#window_del_student').css('top', '253px');
		} else if (who == 'teacher1') {
			$('#window_del_teacher').css('top', '253px');
		} else if (who == 'parent1') {
			$('#window_del_parent').css('top', '253px');
		};
	});

	act = 'get_students';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			a = a.split('%');
			for (var i = 0; i <= a.length-1; i++) {
				$('#students_for_del').append('<option>'+a[i]+'</option>');
			};
		}
	);

	act = 'get_teachers';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			a = a.split('%');
			for (var i = 0; i <= a.length-1; i++) {
				$('#teachers_for_del').append('<option>'+a[i]+'</option>');
			};
		}
	);

	act = 'get_parents';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			a = a.split('%');
			for (var i = 0; i <= a.length-1; i++) {
				$('#parents_for_del').append('<option>'+a[i]+'</option>');
			};
		}
	);

	$('#del_student').click(function(){
		fio = $('#students_for_del').val();
		act = 'delete_student';
		$.post(
			'script.php',
			{
				action: act,
				fio: fio
			}, function(a){
				if (a == 1) {
					$('#window_del_students').css('height', '180px');
					alert('Пользователь удалён!');
					window.location.href = window.location.href;
				};
			}
		);
	});

	$('#del_teacher').click(function(){
		fio = $('#teachers_for_del').val();
		act = 'delete_teachers';
		$.post(
			'script.php',
			{
				action: act,
				fio: fio
			}, function(a){
				if (a == 1) {
					$('#window_del_teachers').css('height', '180px');
					alert('Пользователь удалён!');
					window.location.href = window.location.href;
				};
			}
		);
	});

	$('#del_parent').click(function(){
		fio = $('#parents_for_del').val();
		act = 'delete_parent';
		$.post(
			'script.php',
			{
				action: act,
				fio: fio
			}, function(a){
				if (a == 1) {
					$('#window_del_parents').css('height', '180px');
					alert('Пользователь удалён!');
					window.location.href = window.location.href;
				};
			}
		);
	});
};

//=============================Добавление нового родителя================================
function add_parent(){
	surname = $('#surname_parent').val();
	name = $('#name_parent').val();
	middle_name = $('#middle_name_parent').val();
	fio = surname+' '+name+' '+middle_name;
	child = $('#child').val();
	login = generationLogin(surname, name);
	password = generationPassword();
	console.log(fio+' '+child+' '+login+' '+password);
	act = 'add_new_parent';
	$.post(
		'script.php',
		{
			action: act,
			fio: fio,
			child: child,
			login: login,
			password: password
		}, function(a){
			console.log(a);
			if (a == 1) {
				$('#table_add_parent').html('<tr><td>Логин</td><td>Пароль</td></tr><tr><td>'+login+'</td><td>'+password+'</td></tr>');
				$('#window_add_parent').css('height', '180px');
				d = $('#add_parent').detach();
				$('#close_add_parent').click(function(){
					window.location.href = window.location.href;
				});
			};
		}
	);
};

//=============================Добавление нового учителя================================
function add_teacher(){
	surname = $('#surname_teacher').val();
	name = $('#name_teacher').val();
	middle_name = $('#middle_name_teacher').val();
	fio = surname+' '+name+' '+middle_name;
	// class_number = $('#class_number_student').val();
	count = $('.add_class').attr('count');
	classes = '';
	// console.log(count);
	for (var j = 1; j <= count; j++) {
		classes = classes + $('#select_'+j).val()+'^';
		// console.log(j);
	};
	classes = classes.substring(0, classes.length - 1);

	phone = $('#phone_teacher').val();
	email = $('#email_teacher').val();
	subject = $('#subject_teacher').val();
	// console.log(subject);
	login = generationLogin(surname, name);
	password = generationPassword();
	// console.log(fio+' '+phone+' '+email+' '+classes+' '+login+' '+password);
	act = 'add_new_teacher';
	$.post(
		'script.php',
		{
			action: act,
			fio: fio,
			phone: phone,
			email: email,
			classes, classes,
			subject: subject,
			login: login,
			password: password
		}, function(a){
			console.log(a);
			if (a == 1) {
				$('#table_add_teacher').html('<tr><td>Логин</td><td>Пароль</td></tr><tr><td>'+login+'</td><td>'+password+'</td></tr>');
				$('#window_add_teacher').css('height', '180px');
				d = $('#add_teacher').detach();
				$('#close_add_teacher').click(function(){
					window.location.href = window.location.href;
				});
			};
		}
	);
};

//=============================Добавление нового ученика================================
function add_student(){
	surname = $('#surname').val();
	name = $('#name').val();
	middle_name = $('#middle_name').val();
	fio = surname+' '+name+' '+middle_name;
	class_number = $('#class_number_student').val();
	phone = $('#phone_student').val();
	address = $('#address_student').val();
	login = generationLogin(surname, name);
	password = generationPassword();
	// console.log(fio+' '+class_number+' '+phone+' '+address);
	act = 'add_new_student';
	$.post(
		'script.php',
		{
			action: act,
			fio: fio,
			class_number: class_number,
			phone: phone,
			address: address,
			login: login,
			password: password
		}, function(a){
			// console.log(a);
			if (a == 1) {
				$('#table_add_student').html('<tr><td>Логин</td><td>Пароль</td></tr><tr><td>'+login+'</td><td>'+password+'</td></tr>');
				$('#window_add_student').css('height', '180px');
				d = $('#add_student').detach();
				$('#close_add_student').click(function(){
					window.location.href = window.location.href;
				});
			};
		}
	);
};

//=============================Сохраненине расписания===================================
function save_schedule(weekday, class_number, schedule){
	new_subject = $('#new_subject').val();
	old_subject = $('#old_subject').val();
	for (var i = 0; i <= schedule.length-1; i++) {
		if (schedule[i] == old_subject) {
			schedule[i] = new_subject;
		};
	};
	// console.log(weekday);
	if (weekday == 'Понедельник') {
			day = 'monday';
		} else if (weekday == 'Вторник') {
			day = 'tuesday';
		} else if (weekday == 'Среда') {
			day = 'wednesday';
		} else if (weekday == 'Четверг') {
			day = 'thursday';
		} else if (weekday == 'Пятница') {
			day = 'friday';
		} else if (weekday == 'Суббота') {
			day = 'saturday';
		};
	act = 'update_schedule';
	$.post(
		'script.php',
		{
			action: act,
			weekday: day,
			class_number, class_number,
			schedule: schedule.join('*')
		}, function(a){
			// console.log(a);
			if (a == 1) {
				$('#window_schedule').css('top', '-1000px');
				alert('Расписание изменено!');
				window.location.href = window.location.href;
			};
		}
	);
};

//=============================Вывести инф-ию в форму===================================
function display_information(full_name, address, index, email, phone, director){
	$('#full_name').val(full_name);
	$('#address').val(address);
	$('#index').val(index);
	$('#email').val(email);
	$('#phone').val(phone);
	$('#director').val(director);
};

//=============================Сохранение данных о школе================================
function save_information(){
	name = $('#full_name').val();
	address = $('#address').val();
	index = $('#index').val();
	email = $('#email').val();
	phone = $('#phone').val();
	director = $('#director').val();
	act = 'save_information';
	$.post(
		'script.php',
		{
			action: act,
			name: name,
			address: address,
			index: index,
			email: email,
			phone: phone,
			director: director
		}, function(a){
			if (a == 1) {
				$('#window').css('top', '-1000px');
				alert('Информация успешно изменена!');
			};
		}
	);
};

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

//=============================Выход====================================
function log_out(){
	act = 'log_out';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			window.location.href = 'autorization.html';
		}
	);
};