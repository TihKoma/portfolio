window.onload = function () {
	$('#next').click(function(){
		class_number = $('#class_number').html();
		scheduling(class_number);

	// Переход на след. страницу
		class_number = +class_number + 1;
		if (class_number <= 11) {
			str = "schedule" + class_number + ".html";
			document.location.href = str;
			// console.log(class_number);
		};

	//Завершение
		class_number -= 1;
		if (class_number == 11) {
			// $('body').html('<div style="margin: 100px auto; width: 400px; height: 130px; border: 1px solid black;"><h2 align="center" style="text-decoration: underline;">Расписание заполнено!</h2><br><a href="../index.php" style="float: left; margin-left: 50px;">На главную</a><a href="autorization.html" style="float: right; margin-right: 50px;">Войти в систему</a></div>');
			document.location.href = "end_scheduling.html";
		};
		
	});

	$('.another').click(function(){
		id = $(this).attr('id');
		id_input = caller(id);

		$('#cell_'+id).html('<input type="text" id="'+id_input+'" style="width: 343px; height: 13px;" placeholder="предмет">');
	});
}

//=============================Отправка данных о расписании конкретного класса в БД=========================
//Вход. данные - class_number - номер класса
//==========================================================================================================
function scheduling(class_number){
	var monday = '';
	var tuesday = '';
	var wednesday = '';
	var thursday = '';
	var friday = '';
	var saturday = '';
	var sign;
	for (var i = 1; i <= 8; i++) { //Перебор с 1 по 8 урок
		// day1 = $('#class'+class_number+'_monday_subject_'+i).val();
		// day2 = $('#class'+class_number+'_tuesday_subject_'+i).val();
		// day3 = $('#class'+class_number+'_wednesday_subject_'+i).val();
		// day4 = $('#class'+class_number+'_thursday_subject_'+i).val();
		// day5 = $('#class'+class_number+'_friday_subject_'+i).val();
		// day6 = $('#class'+class_number+'_saturday_subject_'+i).val();

		day1 = $('#monday_subject_'+i).val();
		day2 = $('#tuesday_subject_'+i).val();
		day3 = $('#wednesday_subject_'+i).val();
		day4 = $('#thursday_subject_'+i).val();
		day5 = $('#friday_subject_'+i).val();
		day6 = $('#saturday_subject_'+i).val();

		sign = ((i == 1) || (monday == ''))? '' : '*';
		day1 = (day1 == '...')? '' : sign+day1;
		monday += day1;

		sign = ((i == 1) || (tuesday == ''))? '' : '*';
		day2 = (day2 == '...')? '' : sign+day2;
		tuesday += day2;

		sign = ((i == 1) || (wednesday == ''))? '' : '*';
		day3 = (day3 == '...')? '' : sign+day3;
		wednesday += day3;

		sign = ((i == 1) || (thursday == ''))? '' : '*';
		day4 = (day4 == '...')? '' : sign+day4;
		thursday += day4;

		sign = ((i == 1) || (friday == ''))? '' : '*';
		day5 = (day5 == '...')? '' : sign+day5;
		friday += day5;

		sign = ((i == 1) || (saturday == ''))? '' : '*';
		day6 = (day6 == '...')? '' : sign+day6;
		saturday += day6;
	};
	// console.log(monday);
	// console.log(tuesday);
	// console.log(wednesday);
	// console.log(thursday);
	// console.log(friday);
	// console.log(saturday);
	var act = 'scheduling';


	$.post(
		'../schedule.php',
		{
			action: act,
			class_number: class_number,
			monday: monday,
			tuesday: tuesday,
			wednesday: wednesday,
			thursday: thursday,
			friday: friday,
			saturday: saturday
		}, function(a) {}
	);
}

//====================================================================================================
//==========================Определение по id(ссылки) день недели и номер урока=======================
//Вход. данные - id - номер строки, откуда пришел запрос на изменение формы
//Вых. данные  - id - сформированная строка для id текстового поля(например, class1_monday_subject_1)
function caller(id){
	if (id % 8 == 0) {
		number_day = id/8;
	} else {
		number_day = parseInt(id/8) + 1;
	}
	// console.log(number_day);
	number_subject = id - (number_day - 1) * 8;
	// console.log(number_subject);
	switch (number_day) {
		case 1: weekday = 'monday';
				break;
		case 2: weekday = 'tuesday';
				break;
		case 3: weekday = 'wednesday';
				break;
		case 4: weekday = 'thursday';
				break;
		case 5: weekday = 'friday';
				break;
		case 6: weekday = 'saturday';
	};

	// res = 'class' + number_class + '_' + weekday + '_subject_' + number_subject;
	res = weekday + '_subject_' + number_subject;
	return res;
}