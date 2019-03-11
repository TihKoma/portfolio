window.onload = function() {
	display_news();

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

//================Отображает информацию об родителе==================================
function display_parent(fio, class_number, fio_child){
	$('#fio').html(fio);
	$('#child').html(fio_child);
	$('#class_number').html(class_number);
};