window.onload = function(){
	act = 'get_information_for_student';
	$.post(
		'script.php',
		{
			action: act
		}, function(res){
			// console.log(res);
			if (res == 'error') display_error();
			res = res.split('^');
			student = res[0].split('%');
			display_student(student[0], student[1]);
		}
	);

	display_diary();
	$('#back_page').click(function(){
		currentDate = $('#date_1').html().split('.');
		day = currentDate[0];
		month = currentDate[1];
		year = currentDate[2];
		// switch_back_date(1, 1, 2016);
		backDate = switch_back_date(day, (month-1), year).split('.');
		// console.log(backDate);
		day = backDate[0];
		month = backDate[1];
		year = backDate[2];
		// console.log(year+' '+(month-1)+' '+day);
		display_diary(year, (month-1), day);
	});

	$('#next_page').click(function(){
		currentDate = $('#date_6').html().split('.');
		day = currentDate[0];
		month = currentDate[1];
		year = currentDate[2];
		nextDate = switch_next_date(day, (month-1), year).split('.');
		day = nextDate[0];
		month = nextDate[1];
		year = nextDate[2];
		display_diary(year, (month-1), day);
	});

	$('#log_out').click(function(){
		act = 'log_out';
		$.post(
			'script.php',
			{
				action: act
			}, function(a){
				window.location.href = 'autorization.html';
			}
		);
	});
};

//================Вывод данных в дневник================================================
//year, month, day - необязательные параметры
function display_diary(year, month, day){
	// console.log(year+' '+month+' '+day);

	//Получение расписания
	act = 'get_schedule';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			a = a.split('%'); //Разбиваем на массив(каждая ячейка - строка с расписанием дня)

			dates = generation_date(year, month, day).split(' ');
			// console.log(dates);
			//Вывод предметов в дневник
			for (var j = 0; j <= 5; j++) {
				a[j] = a[j].split('*');
				$('#date_'+(+j+1)).html(dates[j]);
				for (var i = 0; i <= a[j].length-1; i++) {
					$('#day_'+(+j+1)+'_subject_'+i).html(a[j][i]);
					homework(dates[j], (+j+1), i, a[j][i]);
				};
			};
		}
	);
	// homework('17.02.2016', 1, 3, 'Алгебра');
};

//=============Функция, которая достает ДЗ и оценку из БД(если есть) и выводит в дневник=========
//Вход. данные - дата, день недели, номер предмета в расписании, предмет
function homework(date, weekday, number, subject){
	act = 'get_homework_and_mark_for_diary';
	$.post(
		'script.php',
		{
			action: act,
			date: date,
			subject: subject
		}, function(a){
			// console.log(a);
			a = a.split('&');
			// console.log(a);
			$('#homework_day_'+weekday+'_subject_'+number).html('');
			$('#mark_day_'+weekday+'_subject_'+number).html('');
			if (a[0] != 'fail') {
				$('#homework_day_'+weekday+'_subject_'+number).html(a[0]);
			};

			if (a[1] != 'fail') {
				$('#mark_day_'+weekday+'_subject_'+number).html(a[1]);
			};
		}
	);
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

//================Переключение даты на след.======================================
//Вход. данные - месяц, год, число
//Выход. данные - дата дня на след. неделе
function switch_next_date(day, month, year){
	date = new Date(year, month, day);
	if ((date.daysInMonth()-day) < 2) {
		day = 2 - (date.daysInMonth() - day);
		year = (month == 11) ? (+year+1) : (year);
		month = (month == 11) ? (0) : (+month+1);
		// console.log(zero(day) + day + '.' + zero(+month+1) + (+month+1) + '.' + year);
		return (zero(day) + day + '.' + zero(+month+1) + (+month+1) + '.' + year);
	};
	day = +day + +2;
	return (zero(day) + day + '.' + zero(+month+1) + (+month+1) + '.' + year);
};

//================Проверка необходимости нуля перед числом/месяцем==================
function zero(number){
	res = '';
	if (number < 10) {
		res = '0';
	};
	return res;
};

//================Отображает информацию об ученике==================================
function display_student(fio, class_number){
	$('#fio').html(fio);
	$('#class_number').html(class_number);
};

function display_error(){
	$('body').html('<h3 align="center">Ошибка</h3>');
};

Date.prototype.daysInMonth = function() {
	return 32 - new Date(this.getFullYear(), this.getMonth(), 32).getDate();
};