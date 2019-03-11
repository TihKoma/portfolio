window.onload = function() {
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
			id_school = a[1];
			subject = a[2];
			classes = a[3];
			// var full_name = a[4];
			// var address = a[5];
			// var index = a[6];
			// var email = a[7];
			// var phone = a[8];
			// var director = a[9];
			// var count_teachers = a[10];
			// var count_students = a[11];
			// console.log(fio);
			// console.log(priv);
			// console.log(a);
			$('#fio').html(name);
			$('#subject').html(subject);

			display(a[4],a[5],a[6],a[7],a[8],a[9],a[10],a[11]);
			
			classes = classes.split('^');
			console.log(classes);
			for (var i = 0; i <= classes.length-1; i++) {
				$('#list').append('<tr><td><div class="classes" id="class_'+classes[i]+'">'+classes[i]+' класс</a></td></tr>');
			};

			$('.classes').click(function(){
				id = $(this).attr('id');
				class_number = id.split('_')[1];
				set_class(class_number);
				document.location.href = 'journal_teacher.html';
			});
		}
	);

	$('#log_out').click(function(){
		log_out();
	});
}

//==================Запомнить номер класса через сессию===============
function set_class(number_class){
	// console.log('ok');
	$.post(
		'script.php',
		{
			action: 'set_class',
			number_class: number_class
		}
	);
};

//================Отображает информацию о школе на странице=========================
function display(full_name, address, index, email, phone, director, count_teachers, count_students){
	$('#full_name').html(full_name);
	$('#address').html(address);
	$('#index').html(index);
	$('#email').html(email);
	$('#phone').html(phone);
	$('#director').html(director);
	$('#count_teachers').html(count_teachers);
	$('#count_students').html(count_students);
};

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