window.onload = function(){
	$("#save").click(function(){
		save();
	});
};

function save(){
	var login = $("#login").val();
	var surname = $("#surname").val();
	var name = $("#name").val();
	var patronymic = $("#patronymic").val();
	var email = $("#email").val();
	var skype = $("#skype").val();
	var advcash = $("#advcash").val();

	if(check(login, surname, name, patronymic, email, skype, advcash))
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
		'../script.admin.update.inform.php',
		{
			action: "update",
			uid: window.uid,
			login: login,
			surname: surname,
			name: name,
			patronymic: patronymic,
			email: email,
			skype: skype,
			advcash: advcash,
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
function check(login, surname, name, patronymic, email, skype, advcash){
	var err = 0;
	if((login == '') || (surname == '') || (name == '') || (patronymic == '') || (email == '') || (skype == '') || (advcash == '')){
		alert("Ошибка. Данные не введены.");
		return 1;
	};

    var rep = /^[\w\d%$:.-]+@\w+\.\w{2,5}$/;
    if(!rep.test(email)){
    	alert('Ошибка. Не верно введён email.');
		return 1;
    };

    var rep = /^[a-zA-Z0-9]+$/; 
    if(!rep.test(login)){
    	alert('Ошибка. Логин должен состоять только из латинских букв и цифр.');
		return 1;
    };

    var rep = /^[Uu 0-9]+$/; 
    if(!rep.test(advcash)){
    	alert('Ошибка. Номер счёта введён не верно.');
		return 1;
    };
};
