window.onload = function() {
	display_news();

	act = 'get_information';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			// alert(a);
			if (a == 'error') display_error();
			a = a.split('%');
			name = a[0];
			subject = a[2];
			$('#fio').html(name);
			$('#subject').html(subject);
		}
	);

	$('#new_news').click(function(){
		$('#window').css('top', '235px');
	});

	$('#close').click(function(){
		$('#window').css('top', '-1000px');
	});

	$('#save').click(function(){
		save_news();
	});

	$('#log_out').click(function(){
		log_out();
	});
};

//===============================Вывод новостей на страницу=============================
function display_news(){
	act = 'get_news';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			// console.log(a);
			a = a.split('^');
			dates = a[0].split('%');
			titles = a[1].split('*');
			contents = a[2].split('&');
			authors = a[3].split('$');
			$('#news_content').html('');
			for (var i = dates.length-1; i >= 0; i--) {
				$('#news_content').append('<div class="news"><h4 class="header_news">'+titles[i]+'</h4><p class="author">Опубликовал(а) '+authors[i]+' <i>'+dates[i]+'</i></p><p class="content_news">'+contents[i]+'</p></div>');
			};
		}
	);
};

//===============================Сохранение новости=====================================
function save_news(){
	title = $('#name_new_news').val();
	content = $('#content_new_news').val();
	if ((title == '') || (content == '')) {
		alert('Данные не введены');
		return;
	};
	date = new Date();
	day = date.getDate();
	month = +date.getMonth()+1;
	year = date.getFullYear();
	hours = date.getHours();
	minutes = date.getMinutes();
	date_news = zero(day) + day + '.' + zero(month) + month + '.' + year + ' ' + hours + ':' + zero(minutes) + minutes;
	// console.log(date_news);
	act = 'write_news_in_database';
	$.post(
		'script.php',
		{
			action: act,
			title: title,
			content: content,
			date: date_news
		}, function(a){
			// console.log(a);
			if (a == 1) {
				window.location.href = 'news_teacher.html';
			};
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

//============================Ошибка=================================================
function display_error(){
	$('body').html('<h3 align="center">Ошибка</h3>');
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