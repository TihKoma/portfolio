window.onload = function(){
	$("#save").click(function(){
		save();
	});
};

function save(){
	var surname = $("#surname").val();
	var name = $("#name").val();
	var patronymic = $("#patronymic").val();
	var email = $("#email").val();
	var skype = $("#skype").val();

	if(check(surname, name, patronymic, email, skype))
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
		'../script.update.inform.php',
		{
			action: "update",
			surname: surname,
			name: name,
			patronymic: patronymic,
			email: email,
			skype: skype,
			time: time
		}, function(res) {
			if(+res == 1){
				alert('Информация успешна обновлена');
				window.location.href = "account.php";
			} else
				alert(res);
		}
	);
};

//===================================================================================================
//Проверка введённых значений на корректность
function check(surname, name, patronymic, email, skype){
	var err = 0;
	if((surname == '') || (name == '') || (patronymic == '') || (email == '') || (skype == '')){
		alert("Ошибка. Данные не введены.");
		return 1;
	};

    var rep = /^[\w\d%$:.-]+@\w+\.\w{2,5}$/;
    if(!rep.test(email)){
    	alert('Ошибка. Не верно введён email.');
		return 1;
    };
};
