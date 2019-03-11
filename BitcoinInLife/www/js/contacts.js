window.onload = function(){
	$("#mail").click(function(){
		message();
	});
};

function message(){
	var theme = $("#theme").val();
	var message = $("#text").val();
	var fio = $("#fio").html();
	var login = $("#login").html();
	var skype = $("#skype").html();
	if(check(theme, message))
		return;

	$.post(
		'../script.contacts.php',
		{
			action: "mail",
			theme: theme,
			message: message,
			fio: fio,
			login: login,
			skype: skype
		}, function(res){
			if(+res == 1){
				alert('Сообщение успешно доставлено');
				window.location.href = "index.php";
				return;
			};
			alert(res);
		}
	);
};

function check(theme, message){
	if(theme == '' || message == ''){
		alert("Ошибка. Поля не заполнены.");
		return 1;
	};
};