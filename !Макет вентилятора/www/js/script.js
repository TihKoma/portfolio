window.onload = function() {
	vent = 'off';
	speed = 6.25;
	process = false;
	$('#SS_1').click(function(){
		if (vent == 'off') {
			Start(); //Запуск
			writeToDataBase('Запуск установки', '', 'activity_log');
		} else {
			Stop(); //Остановка
			writeToDataBase('Остановка установки', '', 'activity_log');
		}
	})

	season = 'winter';
	$('#WS_1').click(function(){
		selectorSeason('#WS_1', season);
		(season=='summer') ? season = 'winter' : season = 'summer';
	})
//Запуск по расписанию
	$('#activate').click(function(){
		activate();
		deactivate();
	})

	$( "#VEL_1" ).slider({
 		value: 50,
 		min: 0,
 		max: 100,
 		step: 1,
 		slide: function( event, ui ) {
 			speed = ui.value/8; //Сохранение скорости
 			setValue(ui.value);
 			if (vent == 'off') return; //Если установка выкл, то ничего не делать
			clearInterval(animBig);
			a = returnAngle();
 			animateBig('#vent_big', ui.value/8, 1, a);
 		},
 		stop: function(){
 			writeToDataBase('Изменение скорости вращения вентилятора', '', 'activity_log');
 		}
 	});

 	$( "#SetT1" ).slider({
 		value: 25,
 		min: 0,
 		max: 50,
 		step: 1,
 		slide: function( event, ui ) {
 			$('#SET_IND').val(ui.value);
 			difference = Math.floor(Math.random( ) * 3) - 1;
 			$('#TE2').html(ui.value + difference);
 		},
 		stop: function(){
 			writeToDataBase('Изменение значения уставки', '', 'activity_log');
 		}
 	});

 	$('#TE1').html(Math.floor(Math.random( ) * 201) - 100);

 //Режимы АВАРИЙ
 	$('#crash_vent').click(function(){
 		crashVent();
 		Stop('crash');
 	});
 	$('#crash_filter').click(function(){
 		crashFilter();
 		Stop('crash');
 	});
 	$('#crash_freeze').click(function(){
 		crashFreeze();
 		Stop('crash');
 	});
 	$('#crash_flap').click(function(){
 		Stop('crash');
 		crashFlap();
 	});
 	$('#reset').click(function(){
 		document.location.href = document.location.href;//Редирект
 	});

//Журнал
	lastTab = 'all';
	$('#events').click(function(){
		reloadTable('events');
	});

	$('#warning').click(function(){
		reloadTable('warning');
	});

	$('#activity_log').click(function(){
		reloadTable('activity_log');
	});

	$('#all').click(function(){
		reloadTable('all');
	});

	reloadTable('all');
}

//===============================================================================
//=========================Запись в БД и обновление таблицы======================
//txt - текст, который необходимо поместить в БД; status - статус(Акт/Устр или '')
//table - таблица в БД, в которую необходимо поместить txt.
function writeToDataBase(txt, status, table){
	$.post(
		'serverscript.php',
		{insert: txt, status: status, table: table},
		function(){
			reloadTable(table);
		}
	);
}

//===============================================================================
//=========================Обновление журнала====================================
//table - таблица в БД, из которой нужно получить записи
function reloadTable(table){
	$.post(
		"serverscript.php",
		{get: table},
		function(a){
			switcherTabs(table);
			writeToTable(a);
		},
		"html"
	);
}

//===============================================================================
//=========================Переключение вкладок в журнале========================
//===============================================================================
function switcherTabs(tab){
	txt = 'url("img/' + lastTab + '.jpg")';
	lastTab = '#' + lastTab;
	$(lastTab).css('backgroundImage', txt);
	txt = 'url("img/' + tab + '_on.jpg")';
	var tabId = '#' + tab;
	$(tabId).css('backgroundImage', txt);
	lastTab = tab;
	tab = '';
}

//===============================================================================
//=========================Вывод записей из БД в таблицу=========================
//a - строка, результат выборки строк из БД + кол-во этих этих строк
function writeToTable(a){
	$('#journal_table').empty();
	txt = '<tr><td class="menu"><p class="text_menu">ДАТА</p></td><td class="menu"><p class="text_menu">ИСТОЧНИК</p></td><td class="menu" id="message"><p class="text_menu">СООБЩЕНИЕ</p></td><td class="menu"><p class="text_menu">СТАТУС</p></td></tr>';
	$('#journal_table').append(txt);
	a = a.split('%');
//amount - кол-во строк в таблице
	amount = a[0].split('\"')[1];
//arr - строки
	arr = a[1].split('?');
//Удаление лишнего символа "
	arr[amount-1] = arr[amount-1].substring(0, arr[amount-1].length - 2);
	for (var i = 0; i < amount; i++) {
		tdValue = arr[i].split('|');
		if (tdValue[3] == 'act') {
			id = tdValue[4];
			index = tdValue[5];
			tdValue[3] = '<qwe id="' + id + '" index="' + index + '"><input type="checkbox" class="status" onclick="notice(this)" id="' + id + '">' + 'АКТ.</qwe>';
		};
		txt = '<tr><td class=\"cell\">'+tdValue[0]+'</td><td class=\"cell\">'+tdValue[1]+'</td><td class=\"cell\">'+tdValue[2]+'</td><td class=\"cell\">'+tdValue[3]+'</td></tr>';
		$('#journal_table').append(txt);
	};
}

function notice(el){
	id = '#' + el.id;
	index = $(id).attr('index');
	$(id).html('Устр.');
	$.post(
		"serverscript.php",
		{update: 'Устр.', id: el.id, index: index}
	)
}

//===============================================================================
//=========================Генерация случайной строки============================
//===============================================================================
function strRand() {
	var result       = '';
	var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	var max_position = words.length - 1;
	for( i = 0; i < 5; ++i ) {
		position = Math.floor ( Math.random() * max_position );
		result = result + words.substring(position, position + 1);
	}
	return result;
}

//===============================================================================
//=========================Авария Заслонки=======================================
//===============================================================================
function crashFlap(){
	$('#SState_1').html('АВАРИЯ ЗАСЛОНКИ').css('color', 'red');
	$('#damper_img').css('backgroundImage', 'url("img/crash.jpg")').css('width', '100px');
	$('#damper').css('width', '100px');
	writeToDataBase('АВАРИЯ ЗАСЛОНКИ', 'act', 'warning');
	writeToDataBase('Установка «СТОП»', '', 'warning');
	crash = true;
}

//===============================================================================
//=========================Авария Заморозка======================================
//===============================================================================
function crashFreeze(){
	$('#SState_1').html('АВАРИЯ ЗАМОРОЗКА').css('color', 'red');
	$('#THERM_IND').css('backgroundImage', 'url("img/freeze.jpg")').css('width', '134px');
	writeToDataBase('АВАРИЯ ЗАМОРОЗКА', 'act', 'warning');
	writeToDataBase('Установка «СТОП»', '', 'warning');
}

//===============================================================================
//=========================Авария Фильтра========================================
//===============================================================================
function crashFilter(){
	$('#SState_1').html('АВАРИЯ ФИЛЬТРА').css('color', 'red');
	$('#F11').css('backgroundImage', 'url("img/clog.jpg")');
	writeToDataBase('Засор фильтра', '', 'events');
}

//===============================================================================
//=========================Авария Вентилятора====================================
//===============================================================================
function crashVent(){
	$('#SState_1').html('АВАРИЯ ВЕНТИЛЯТОРА').css('color', 'red');
	$('#speed_vent').css('backgroundImage', 'url("img/crash.jpg")').html('').css('border', '1px solid black');
	writeToDataBase('АВАРИЯ ВЕНТИЛЯТОРА', 'act', 'warning');
	writeToDataBase('Установка «СТОП»', '', 'warning');
}

//===============================================================================
//=========================Функция вывода значений скорости======================
//value - значение, которое нужно установить #VEL_IND и #speed
function setValue(value){
	$('#VEL_IND').val(value);
	$('#speed').html(value);
}

//===============================================================================
//==========Анимация запуска системы (строка процесса) + Запуск самой системы=====
//===============================================================================
function Start(){
	if (process) return;//Если идет процесс запуска/остановки, то ничего не делать
	process = true;
	selectorVent('#SS_1', vent);
	$('#start').css('top', '220px');
	$('#header_process').html('Запуск установки');
	$('#process').empty();
	amountBrick = 0;
	animStart = setInterval(
		function(){
			if (amountBrick == 13) {
				$('#process').append('<div class="brick" id="end"></div>');
				clearInterval(animStart);
				$('#start').css('top', '-1000px');
				startSystem();
				process = false;
			} else {
				$('#process').append('<div class="brick"></div>');
				amountBrick++;
			}
		},
		100
	)
}

//===============================================================================
//=========================Функция запуска установки=============================
//===============================================================================
function startSystem(){
	vent = 'on';
	if (!crash)
		$('#damper_img').css('backgroundImage', 'url("img/damper_open.jpg")');
	$('#INC_IND').css('backgroundImage', 'url("img/damper_on.png")');
	$('#OUT_IND').css('backgroundImage', 'url("img/out.png")');
	(self.angle) ? b = returnAngle() : b = 0; //b - стартовый угол поворота
//==========Вращение малого вентилятора=====================
	animateSmall('#FUN_1', 10, 20);
//==========Вращение большого вентилятора===================
	animateBig('#vent_big', speed, 1, b);
}

//===============================================================================
//====Анимация остановки системы (строка процесса) + остановка самой системы=====
//status - какая должна быть выполнена остановка(обычная/аварийная) (norm/crash)
function Stop(status){
	if (process) return;
	if (vent == 'off') return; //Если установка не запущена, то останавливать ее не надо
	process = true;
	if (status == 'crash') 
		txt = 'Аварийная остановка установки';
	else
		txt = 'Остановка установки';
	selectorVent('#SS_1', vent);
	$('#start').css('top', '220px');
	$('#header_process').html(txt);
	$('#process').empty();
	amountBrick = 0;
	animStart = setInterval(
		function(){
			if (amountBrick == 13) {
				$('#process').append('<div class="brick" id="end"></div>');
				clearInterval(animStart);
				$('#start').css('top', '-1000px');
				stopSystem();
				process = false;
			} else {
				$('#process').append('<div class="brick"></div>');
				amountBrick++;
			}
		},
		100
	)
}

//===============================================================================
//=========================Функция остановки установки=============================
//===============================================================================
function stopSystem(){
	vent = 'on';
	selectorVent('#SS_1', vent);
	vent = 'off';
	if (season == 'winter') {
		if (!crash)
			$('#damper_img').css('backgroundImage', 'url("img/damper_close.jpg")');
		$('#WARM_IND').css('backgroundImage', 'url("img/blok_red.png")');
		$('#COOL_IND').css('backgroundImage', 'url("img/cool_black.png")');
	} else {
		$('#damper_img').css('backgroundImage', 'url("img/damper_open.jpg")');
		$('#WARM_IND').css('backgroundImage', 'url("img/blok_black.png")');
		$('#COOL_IND').css('backgroundImage', 'url("img/cool_blue.png")');
	}
	$('#INC_IND').css('backgroundImage', 'url("img/damper_off.png")');
	$('#OUT_IND').css('backgroundImage', 'none');
	clearAnimate();
}

//===============================================================================
//================Переключение старт/стоп========================================
//id - идентификатор, value - значение(on/off)
function selectorVent(id, value){
	if (value == 'on') {
		$(id).attr('src','img/stop.png');
	} else if (value == 'off') {
		$(id).attr('src','img/start.png');
	}
}

//===============================================================================
//================Переключение лето/зима=========================================
//id - идентификатор переключателя, value - значение(summer/winter)
function selectorSeason(id, value){
	if (value == 'winter') {
		$('#WS_IND').attr('src','img/summer.png');
		$(id).attr('src','img/start.png');
		summer();
		writeToDataBase('Переход установки в летний режим работы', '', 'activity_log');
	} else {
		$('#WS_IND').attr('src','img/winter.png');
		$(id).attr('src','img/stop.png');
		winter();
		writeToDataBase('Переход установки в зимний режим работы', '', 'activity_log');
	}
}

//===============================================================================
//================Значения всех элементов при зиме===============================
//===============================================================================
function winter(){
	if (vent == 'off' && !crash) {
		$('#damper_img').css('backgroundImage', 'url("img/damper_close.jpg")');
	};
	$('#WARM_IND').css('backgroundImage', 'url("img/blok_red.png")');
	$('#COOL_IND').css('backgroundImage', 'url("img/cool_black.png")');
	$('#PUMP_IND').css('backgroundImage', 'url("img/on.jpg")');
	$('#HOTFLOW_IND').css('backgroundImage', 'url("img/hot_air.png")');
	$('#COLDFLOW_IND').css('backgroundImage', 'none');
}

//===============================================================================
//================Значения всех элементов в лето=================================
//===============================================================================
function summer(){
	if (!crash)
		$('#damper_img').css('backgroundImage', 'url("img/damper_open.jpg")');
	$('#WARM_IND').css('backgroundImage', 'url("img/blok_black.png")');
	$('#COOL_IND').css('backgroundImage', 'url("img/cool_blue.png")');
	$('#PUMP_IND').css('backgroundImage', 'url("img/off.jpg")');
	$('#HOTFLOW_IND').css('backgroundImage', 'none');
	$('#COLDFLOW_IND').css('backgroundImage', 'url("img/cold_air.png")');
}

//===============================================================================
//================Анимация малого вентилятора(вращение)==========================
//id - идентификатор, corner - угол поворота вентилятора, period - время поворота
function animateSmall(id, corner, period){
	var txt;
	var angle = corner; //angle - изменяемый угол
	var time = period;  //Частота поворотов(время)
	animSmall = setInterval(
		function(){
			txt = 'rotate(' + angle + 'deg' + ')';
			$(id).css('transform', txt);
			angle += corner;
		},
		time
	);


}

//===============================================================================
//================Анимация большого вентилятора(вращение)========================
//id - идентификатор, corner - угол поворота вентилятора, period - время поворота
//startAngle - начальный угол наклона вентилятора(для скорости)
function animateBig(id, corner, period, startAngle){
	var txt;
	angle = startAngle; //angle - изменяемый угол
	var time = period;  //Частота поворотов(время)
	animBig = setInterval(
		function(){
			txt = 'rotate(' + angle + 'deg' + ')';
			$(id).css('transform', txt);
			angle += corner;
		},
		time
	);
}

//===============================================================================
//====Возвращает угол поворота вентилятора(исп при изменении скорости)===========
//===============================================================================
function returnAngle(){
	return angle;
}


//===============================================================================
//================Остановка вентилятора==========================================
//===============================================================================
function clearAnimate(){
	if (self.animSmall) 
		clearInterval(animSmall);
	if (self.animBig)
		clearInterval(animBig);
}

//===============================================================================
//==================Функция активации установки по таймеру=======================
//===============================================================================
function activate(){
	var time_on = $('#time_on').val();
	if (time_on == '') {
		alert('Необходимо указать время запуска и остановки');
		return;
	};
	writeToDataBase('Активация режима работы по расписанию', '', 'activity_log');
	var date = new Date();
//time_start - время до запуска системы в миллисекундах
	hours = +(time_on[0] + time_on[1]) - date.getHours();
	time_start = hours * 3600000;
	minutes = +(time_on[3] + time_on[4]) - date.getMinutes();
	time_start += minutes * 60000;
	alert('Установка будет запущена через ' + hours + ' часов ' + minutes + ' минут');
	time_start -= date.getSeconds() * 1000;
	startup = setTimeout(
		function() {
			Start();
		}, 
		time_start
	);
}

//===============================================================================
//==================Функция остановки установки по таймеру=======================
//===============================================================================
function deactivate(){
	var date = new Date();
	var time_off = $('#time_off').val();
//time_stop - время до остановки системы в миллисекундах
	time_stop = (+(time_off[0] + time_off[1]) - date.getHours()) * 3600000;
	time_stop += (+(time_off[3] + time_off[4]) - date.getMinutes()) * 60000;
	time_stop -= date.getSeconds() * 1000;
	stopover = setTimeout(
		function() {
			Stop();
		}, 
		time_stop
	);
}