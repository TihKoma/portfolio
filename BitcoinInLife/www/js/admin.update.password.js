window.onload = function(){
	$("#save").click(function(){
		save();
	});
};

function save(){
	var password = $("#password").val();
	var password2 = $("#password2").val();

	if(check(password, password2))
		return;

	var date = new Date();
	var options = {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
		timezone: 'UTC',
		hour: 'numeric',
		minute: 'numeric',
		second: 'numeric'
	};
	var time = date.toLocaleString("ru", options);

	$.post(
		'../script.admin.update.password.php',
		{
			action: "update",
			uid: window.uid,
			password: password,
			time: time
		}, function(res) {
			if(+res == 1){
				alert('Информация успешна обновлена');
				var link = "user.page.php?uid=" + window.uid;
				window.location.href = link;
			} else
				alert(res);
		}
	);
};

//===================================================================================================
//Проверка введённых значений на корректность
function check(password, password2){
	var err = 0;
	if((password == '') || (password2 == '')){
		alert("Ошибка. Данные не введены.");
		return 1;
	};

	if(password != password2){
		alert('Ошибка. Пароли не совпадают.');
		return 1;
	};

	if(password.length < 6){
		alert('Ошибка. Слишком короткий пароль.');
		return 1;
	};
};
