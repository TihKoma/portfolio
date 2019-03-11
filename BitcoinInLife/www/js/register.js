window.onload = function(){
	// $("#reg_but").click(register());
	$('#reg_but').click(function(){
		register();
	});
};

//===================================================================================================
//Регистрация нового пользователя
function register(){
	var surname = $("#surname").val();
	var name = $("#name").val();
	var patronymic = $("#patronymic").val();
	var login = $("#login").val();
	var password = $("#password").val();
	var password2 = $("#password2").val();
	var email = $("#email").val();
	var skype = $("#skype").val();
	var advcash = $("#advcash").val();
	var code = $("#code").val();

	// console.log(surname+' '+name+' '+patronymic+' '+login+' '+password+' ' +password2+' '+email+' '+code);
	if(check(surname, name, patronymic, login, password, password2, email, skype, advcash, code))
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
		'../php/register.php',
		{
			action: "register",
			surname: surname,
			name: name,
			patronymic: patronymic,
			login: login,
			password: password,
			email: email,
			skype: skype,
			advcash: advcash,
			time: time,
			parent: window.parent
		}, function(res) {
			if(+res == 1){
				alert('Регистрация успешно завершена');
				window.location.href = "autorization.php";
			} else
				alert(res);
		}
	);
};
//===================================================================================================

//===================================================================================================
//Проверка введённых значений на корректность
function check(surname, name, patronymic, login, password, password2, email, skype, advcash, code){
	var err = 0;
	if((surname == '') || (name == '') || (patronymic == '') || (login == '') || (password == '') || (password2 == '') || (email == '') || (skype == '') || (advcash == '') || (code == '')){
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

	if(code != 'sasay'){
		alert('Ошибка. Не верно введёны символы.');
		$("#code").css("border", "1px solid red");
		$("#code").css("width", "282px");
		return 1;
	};

	var rep = /^[a-zA-Z0-9]+$/; 
    if(!rep.test(login)){
    	alert('Ошибка. Логин должен состоять только из латинских букв и цифр.');
    	$("#login").css("border", "1px solid red");
		$("#login").css("width", "502px");
		return 1;
    };
    var rep = /^[\w\d%$:.-]+@\w+\.\w{2,5}$/;
    if(!rep.test(email)){
    	alert('Ошибка. Не верно введён email.');
		return 1;
    };

    if(login.length > 15){
    	alert('Ошибка. Длина логина не должна превышать 15 символов.');
    	return 1;
    };
};
