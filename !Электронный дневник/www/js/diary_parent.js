window.onload = function(){
	var first_day;
	var last_day;
	act = 'get_information_for_parent';
	$.post(
		'script.php',
		{
			action: act
		}, function(res){
			console.log(res);
			if (res == 'error') display_error();
			res = res.split('^');
			parent = res[0].split('%');
			school = res[1].split('%');
			display_parent(parent[0], parent[1], parent[2]);
		}
	);

	//Получение оценок из БД
	act = 'get_marks_and_comments';
	$.post(
		'script.php',
		{
			action: act
		}, function(res){
			console.log(res);
			res = res.split('@');
			a = res[0].split('^');
			marks = a[0].split('&');
			date_mark = a[1].split('%');
			subjects_marks = a[2].split('$');
			dates = generation_date().split(' ');
			// console.log(date);
			window.first_day = dates[0];
			window.last_day = dates[dates.length-1];
			// console.log(dates[dates.length-1]);
			console.log(window.first_day+' '+window.last_day);
			for (var i = 0; i <= marks.length-1; i++) {
				if (in_array(date_mark[i], dates)) {
					$('#perfomance').append('<tr><td>'+date_mark[i]+'</td><td>'+subjects_marks[i]+'</td><td>'+marks[i]+'</td></tr>');
				};
			};

			b = res[1].split('^');
			date_comments = b[0].split('%');
			comments = b[1].split('&');
			subjects = b[2].split('$');
			fio_teachers = b[3].split('*');
			for (var i = 0; i <= date_comments.length-1; i++) {
				if (in_array(date_comments[i], dates)) {
					$('#table_comments').append('<tr><td>'+date_comments[i]+'</td><td>'+subjects[i]+'</td><td>'+fio_teachers[i]+'</td><td>'+comments[i]+'</td></tr>');
				};
			};

			//marks, date_mark, subjects видны только отсюда
			$('#next_page').click(function(){
				switch_next(marks, date_mark, subjects_marks, date_comments, comments, subjects, fio_teachers);
			});

			$('#back_page').click(function(){
				switch_back(marks, date_mark, subjects_marks, date_comments, comments, subjects, fio_teachers);
			});
		}
	);

	$('#log_out').click(function(){
		log_out();
	});
};

//========================Переключение страницы на след.=============================
function switch_next(marks, date_mark, subjects, date_comments, comments, subjects_comments, fio_teachers){
	day = window.last_day.split('.')[0];
	month = window.last_day.split('.')[1];
	year = window.last_day.split('.')[2];
	dates = generation_date(year, (month-1), (+day+2)).split(' ');
	// console.log(dates);
	// console.log(window.first_day+' '+window.last_day);
	$('#perfomance').html('<tr><td class="date_title">Дата</td><td id="subject_title">Предмет</td><td id="mark_title">Оценка</td></tr>');
	for (var i = 0; i <= marks.length-1; i++) {
		if (in_array(date_mark[i], dates)) {
			$('#perfomance').append('<tr><td>'+date_mark[i]+'</td><td>'+subjects[i]+'</td><td>'+marks[i]+'</td></tr>');
		};
	};

	$('#table_comments').html('<tr><td class="date_title">Дата</td><td>Предмет</td><td>Учитель</td><td>Замечание</td></tr>');
	for (var i = 0; i <= date_comments.length-1; i++) {
		if (in_array(date_comments[i], dates)) {
			$('#table_comments').append('<tr><td>'+date_comments[i]+'</td><td>'+subjects_comments[i]+'</td><td>'+fio_teachers[i]+'</td><td>'+comments[i]+'</td></tr>');
		};
	};

	window.first_day = dates[0];
	window.last_day = dates[dates.length-1];
};

//========================Переключение страницы на пред.=============================
function switch_back(marks, date_mark, subjects, date_comments, comments, subjects_comments, fio_teachers){
	day = window.first_day.split('.')[0];
	month = window.first_day.split('.')[1];
	year = window.first_day.split('.')[2];
	backDate = switch_back_date(day, (month-1), year).split('.');
	day = backDate[0];
	month = backDate[1];
	year = backDate[2];
	// console.log(backDate);
	dates = generation_date(year, (month-1), day).split(' ');
	// console.log(dates);
	// console.log(window.first_day+' '+window.last_day);
	$('#perfomance').html('<tr><td class="date_title">Дата</td><td id="subject_title">Предмет</td><td id="mark_title">Оценка</td></tr>');
	for (var i = 0; i <= marks.length-1; i++) {
		if (in_array(date_mark[i], dates)) {
			// console.log(subjects[i]);
			$('#perfomance').append('<tr><td>'+date_mark[i]+'</td><td>'+subjects[i]+'</td><td>'+marks[i]+'</td></tr>');
		};
	};
	
	$('#table_comments').html('<tr><td class="date_title">Дата</td><td>Предмет</td><td>Учитель</td><td>Замечание</td></tr>');
	for (var i = 0; i <= date_comments.length-1; i++) {
		if (in_array(date_comments[i], dates)) {
			console.log(comments[i]);
			$('#table_comments').append('<tr><td>'+date_comments[i]+'</td><td>'+subjects_comments[i]+'</td><td>'+fio_teachers[i]+'</td><td>'+comments[i]+'</td></tr>');
		};
	};

	window.first_day = dates[0];
	window.last_day = dates[dates.length-1];
};

//================Отображает информацию об родителе==================================
function display_parent(fio, class_number, fio_child){
	$('#fio').html(fio);
	$('#child').html(fio_child);
	$('#class_number').html(class_number);
};

function display_error(){
	$('body').html('<h3 align="center">Ошибка</h3>');
};

//===============================Формирование дат для дневника=================================
//Вход. данные - год, месяц, день текущей даты(необязательные параметры); исп. при прокрутке
//Выход. данные - строка с датами дней текущей недели разделенная ' '
function generation_date(year, month, day){
	year = year || '0';
	month = month || '0';
	day = day || '0';
	//Проверка существования вход. данных
	if ((year == '0') && (month == '0') && (day == '0')) {
		date = new Date();// Сегодняшняя дата
	} else {
		date = new Date(year, month, day); //Установленная дата
	};
	today = date.getDate();//День месяца
	month = date.getMonth();//Текущий Месяц
	today_weekday = date.getDay();//Сегодняшний день недели
	
	count_day_before_today = ((today_weekday == 0) ? (7) : (today_weekday)) - 1;
	first_day = today - count_day_before_today;
	count = 1;//Счетчик для вывода дат в эл. с id="date_(от 1 до 7)"
	var res = '';
	for (var i = first_day; i <= date.daysInMonth(); i++) {
		if (count > 6) break;
		res = res + zero(i) + i + '.' + zero(+month+1) + (+month+1) + '.' + date.getFullYear() + ' ';
		count += 1;
	};
	res2 = '';
	if (count < 6) {
		monthYear = switch_date(month, date.getFullYear()); // месяц и год в связке
		month = monthYear.split('.')[0];
		year = monthYear.split('.')[1];
		date = new Date(year, month, 1);//Начало месяца
		for (var i = 1; i <= date.daysInMonth(); i++) {
			if (count > 6) break;
			res2 = res2 + zero(i) + i + '.' + zero(+month+1) + (+month+1) + '.' + year + ' ';
			count += 1;
		};
	} else {
		res = res.substring(0, res.length - 1); //Укорачиваем
	};
	res2 = res2.substring(0, res2.length - 1);
	// console.log(res+res2);
	return (res+res2);
};

//================Переключение даты на след.======================================
//Вход. данные - месяц, год
function switch_date(month, year){
	if (month == 11) {
		resMonth = 0;
		resYear = +year + +1;
		return (resMonth+'.'+resYear);
	};
	resMonth = +month + +1;
	return (zero(resMonth)+resMonth+'.'+year);
};

//================Переключение даты на пред.======================================
//Вход. данные - месяц, год, число
//Выход. данные - дата дня на пред. неделе
function switch_back_date(day, month, year){
	if (day <= 7) { // Т.к. вычитаем 3 дня
		year = (month == 0) ? (year-1) : (year);
		month = (month == 0) ? (11) : (month-1);
		date = new Date(year, month);
		maxDay = date.daysInMonth();
		day = maxDay + (day-7);
		// console.log(zero(day) + day + zero(month) + month + year);
	} else {
		day -= 7;
		// console.log(zero(day) + day + zero(month) + month + year);
	};
	return (zero(day) + day + '.' + zero(+month+1) + (+month+1) + '.' + year);
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

//================Проверка необходимости нуля перед числом/месяцем==================
function zero(number){
	res = '';
	if (number < 10) {
		res = '0';
	};
	return res;
};

Date.prototype.daysInMonth = function() {
	return 32 - new Date(this.getFullYear(), this.getMonth(), 32).getDate();
};

//================Проверка вхождения элемента в массив===============================
function in_array(value, array) 
{
    for(var i = 0; i < array.length; i++) 
    {
        if(array[i] == value) return true;
    }
    return false;
};

