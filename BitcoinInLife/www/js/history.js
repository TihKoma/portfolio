window.mas = []; //Массив id записей из БД, которые необходимо удалить
window.N = 0;

window.onload = function(){
	$(".history").click(function(event){
		event = event || window.event;
		var id = event.target.getAttribute('id'); //id элемента
		var num = (id.split('_'))[1]; //id записи в БД
		window.mas[N] = num;
		window.N++;
	});

	$("#delete").click(function(){
		$.post(
		'../script.history.php',
		{
			mas: window.mas,
			count: window.N
		}, function(res){
			if(+res == 1){
				window.location.href = "history.php";
				return;
			};
			if(+res == -1)
				return;
			alert(res);
		}
	);
	});
};