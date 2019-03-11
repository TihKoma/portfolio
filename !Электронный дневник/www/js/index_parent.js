window.onload = function(){
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
			display(school[0],school[1],school[2],school[3],school[4],school[5],school[6],school[7]);
		}
	);
	$('#diary').click(function(){
		window.location.href = 'diary_parent.html';
	});

	$('#log_out').click(function(){
		log_out();
	});
};

//================Отображает информацию об родителе==================================
function display_parent(fio, class_number, fio_child){
	$('#fio').html(fio);
	$('#child').html(fio_child);
	$('#class_number').html(class_number);
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