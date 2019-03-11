window.onload = function() {
	display_themes();
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

	$('#new_theme').click(function(){
		$('#window').css('top', '235px');
	});

	$('#close').click(function(){
		$('#window').css('top', '-1000px');
	});

	$('#save').click(function(){
		save_theme();
	});

	// $('.links').click(function(){
	// 	alert('werty');
	// 	$('body').html('');
	// });

	$('.links').click(function(){
		id = $(this).attr('id');
		id_theme = id.split('_')[0];
		alert(id_theme);
	});

	$('#log_out').click(function(){
		log_out();
	});
};

//======================Вывод на экран всех тем=====================================
function display_themes(){
	act = 'get_themes';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			a = a.split('^');
			id = a[0].split('#');
			dates = a[1].split('%');
			titles = a[2].split('*');
			authors = a[3].split('$');
			$('#all_themes').html('');
			for (var i = 0; i <= id.length-1; i++) {
				$('#all_themes').append('<div id="theme_'+id[i]+'" class="links">'+titles[i]+'</div> <br>');
			};
			$('body').append('<script>$(\'.links\').click(function(){id = $(this).attr(\'id\');id_theme = id.split(\'_\')[1];display_specific_theme(id_theme)});</script>');
		}
	);
};

//======================Вывод конкретной темы=======================================
function display_specific_theme(id_theme){
	act = 'get_specific_theme';
	$.post(
		'script.php',
		{
			action: act,
			id_theme: id_theme
		}, function(a){
			console.log(a);
			a = a.split('^');
			date = a[0];
			title = a[1];
			content = a[2];
			author = a[3];
			$('#content').html('<h3 align="center">'+title+'</h3><div id="content_theme">'+content+'</div><p class="date" style="float: left; margin-left: 150px; margin-top: -40px;">Создал(а) <i>'+author+'</i></p><p class="date" style="float: right; margin-right: 150px; margin-top: -40px;">'+date+'</p><div id="all_comments"></div><div id="new_comments"><h4 align="center">Новый комментарий</h4><textarea id="content_new_comment"></textarea><div id="save_new_comment">Комментировать</div></div></div>');

			date_comments = a[4].split('%');
			content_comments = a[5].split('&');
			author_comments = a[6].split('$');
			for (var i = 0; i <= date_comments.length-1; i++) {
				$('#all_comments').append('<div class="comments"><p><i>'+author_comments[i]+'</i> написал(а)</p><p class="content_comments">'+content_comments[i]+'</p><p class="date">'+date_comments[i]+'</p></div>');
			};
			$('#save_new_comment').click(function(){
				save_new_comment(id_theme);
			});
		}
	);
};

//======================Сохранение комментария======================================
function save_new_comment(id_theme){
	content = $('#content_new_comment').val();
	if (content == '') {
		alert('Поле не заполнено');
		return;
	};
	date = new Date();
	day = date.getDate();
	month = +date.getMonth()+1;
	year = date.getFullYear();
	hours = date.getHours();
	minutes = date.getMinutes();
	date_comment = zero(day) + day + '.' + zero(month) + month + '.' + year + ' ' + hours + ':' + zero(minutes) + minutes;
	act = 'save_new_comment';
	$.post(
		'script.php',
		{
			action: act,
			id_theme: id_theme,
			date: date_comment,
			content: content
		}, function(a){
			console.log(a);
			// date_comments = a[4].split('%');
			// content_comments = a[5].split('&');
			author_comment = a;
			// alert(date_comment+' '+content+' '+author_comment);
			$('#all_comments').append('<div class="comments"><p><i>'+author_comment+'</i> написал(а)</p><p class="content_comments">'+content+'</p><p class="date">'+date_comment+'</p></div>');
			$('#content_new_comment').val('');
		}
	);
};

//======================Сохранение темы=============================================
function save_theme(){
	title = $('#name_new_theme').val();
	content = $('#content_new_theme').val();
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
	date_theme = zero(day) + day + '.' + zero(month) + month + '.' + year + ' ' + hours + ':' + zero(minutes) + minutes;
	act = 'write_theme_in_database';
	$.post(
		'script.php',
		{
			action: act,
			title: title,
			content: content,
			date: date_theme
		}, function(a){
			console.log(a);
			if (a == 1) {
				window.location.href = 'forum_teacher.html';
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