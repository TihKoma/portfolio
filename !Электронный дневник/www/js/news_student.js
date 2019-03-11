window.onload = function() {
	display_news();

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
			school = res[1].split('%');
			display_student(student[0], student[1]);
		}
	);

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

//================Отображает информацию об ученике==================================
function display_student(fio, class_number){
	$('#fio').html(fio);
	$('#class_number').html(class_number);
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