window.onload = function() {
	act = 'get_information_for_admin';
	$.post(
		'script.php',
		{
			action: act
		}, function(a){
			console.log(a);
			if (a == 'error') display_error();
			school = a.split('%');
			display(school[0],school[1],school[2],school[3],school[4],school[5],school[6],school[7]);
		}
	);

	$('#log_out').click(function(){
		log_out();
	});
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