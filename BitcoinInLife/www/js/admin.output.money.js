window.mas = []; //Массив id записей из БД, которые необходимо удалить
window.N = 0;

window.onload = function(){
	$(".request").click(function(event){
		event = event || window.event;
		var id = event.target.getAttribute('id'); //id элемента
		var num = (id.split('_'))[1]; //id записи в БД
		window.mas[N] = num;
		window.N++;
	});



	$(".confirm").click(function(event){
		event = event || window.event;
		var id = event.target.getAttribute('id'); //id записи
		var uid = event.target.getAttribute('uid'); //user id
		var count = event.target.getAttribute('count'); //сумма $

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
			'../script.admin.output.money.php',
			{
				id: id,
				uid: uid,
				count: count,
				time: time
			}, function(res){
				if(+res == 1){
					window.location.href = "admin.output.money.php";
					return;
				};
				if(+res == -1)
					return;
				alert(res);
			}
		);
	});

	$("#delete").click(function(){
		var act = 'delete';
		$.post(
		'../script.admin.output.money.php',
		{
			action: act,
			mas: window.mas,
			count: window.N
		}, function(res){
			if(+res == 1){
				window.location.href = "admin.output.money.php";
				return;
			};
			if(+res == -1)
				return;
			alert(res);
		}
	);
	});
};