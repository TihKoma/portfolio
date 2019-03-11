window.onload = function(){
	$("#save").click(function(){
		save();
	});
};

function save(){
	var old_password = $("#old_password").val();
	var password = $("#password").val();
	var password2 = $("#password2").val();

	if(check(old_password, password, password2))
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
		'../script.update.password.php',
		{
			action: "update",
			old_password: old_password,
			password: password,
			time: time
		}, function(res) {
			if(+res == 1){
				alert('Пароль успешно изменён');
				window.location.href = "account.php";
			} else
				alert(res);
		}
	);
};

//===================================================================================================
//Проверка введённых значений на корректность
function check(old_password, password, password2){
	var err = 0;
	if((old_password == '') || (password == '') || (password2 == '')){
		alert("Ошибка. Данные не введены.");
		return 1;
	};

    if(password != password2){
    	alert("Ошибка. Пароли не совпадают.");
    	return 1;
    };

    if(password.length < 6){
    	alert("Ошибка. Слишком короткий пароль.");
    	return 1;
    };
};
