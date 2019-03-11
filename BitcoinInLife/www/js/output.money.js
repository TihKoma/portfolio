window.onload = function(){
	$("#submit").click(function(){
		output_money();
	});
};

function output_money(){
	var count = $("#count").val();

	if(+count == 0){
		alert('Ошибка. Неправильно введена сумма.');
		return;
	};

	$.post(
		'script.output.money.php',
		{
			action: 'output',
			count: count
		}, function(res) {
			if(+res == 1){
				alert("Запрос успешно отправлен");
				window.location.href = "account.php";
			} else
				alert(res);
		}
	);
};