window.onload = function(){
	$("#entrance").click(function(){
		entrance();
	});
};

//===================================================================================================
//Вход в систему
function entrance(){
	var login = $("#login").val();
	var password = $("#password").val();

	$.post(
		'../script.autorization.php',
		{
			action: "autorization",
			login: login,
			password: password
		}, function(res){
			if(+res == 1){
				var link = (login == 'admin') ? "admin.php" : "account.php";
				window.location.href = link;
				return;
			};
			alert(res);
		}
	);
};