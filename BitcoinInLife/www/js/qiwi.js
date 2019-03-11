window.res = 0;
window.err = 0;
window.onload = function(){

	$("input")[0].onkeypress = function(e){
		e = e || event;
		if (e.ctrlKey || e.altKey || e.metaKey) return;
		var chr = getChar(e);
		if (chr == null) return;
		if ((chr < '0' || chr > '9') && chr != '+') {
			return false;
		};
	};

	$("input")[1].onkeypress = function(e){
		e = e || event;
		if (e.ctrlKey || e.altKey || e.metaKey) return;
		var chr = getChar(e);
		if (chr == null) return;
		if (chr < '0' || chr > '9') {
			return false;
		}
	};

	$("#submit").click(function(){
		if(check_cnt())
			return;
		var card = $("#qiwi").val();
		var cnt = $("#cnt").val();
		

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
			'../script.input.money.php',
			{
				card: card,
				cnt: cnt,
				time: time,
				payment: "Qiwi Wallet"
			}, function(res){
				if(+res){
					alert("Заявка отправлена. Ожидайте начисления $.");
					window.location.href = "account.php";
				} else
					alert(res);

			}
		);

	});

};



function getChar(event) {
	if (event.which == null) {
		if (event.keyCode < 32) return null;
		return String.fromCharCode(event.keyCode) // IE
	}
	if (event.which != 0 && event.charCode != 0) {
		if (event.which < 32) return null;
		return String.fromCharCode(event.which) // остальные
	}
	return null; // специальная клавиша
};

function check_cnt(){
	var cnt = $("#cnt").val(); 		//Количество
    var card = $("#qiwi").val(); 	//Карта

	if(!card || !cnt){
		alert('Ошибка. Данные не введены.');
		return 1;
	};

//============================
	var rep = /^[0-9]+$/; 
    if(!rep.test(cnt)){
    	alert('Ошибка. Должно быть указано число.');
		return 1;
    };
    if(+cnt == 0){
    	alert('Ошибка');
    	return 1;
    };


//============================
	var rep = /^[+0-9]+$/; 
    if(!rep.test(card)){
    	alert('Ошибка. Номер карты/счёта введён не корректно.');
		return 1;
    };
    return 0;
};