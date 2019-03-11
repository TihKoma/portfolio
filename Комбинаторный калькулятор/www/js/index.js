window.flag = 0; 		// 0 - факториал; 1 - суперфакториал, 2 - гиперфакториал, 3 - примориал,
//4 - Убывающий факториал, 5 - возрастающий факториал, 6 - Размещения с повторениями, 7 - Размещения без повторений
//8 - Перестановки без повторений, 9 - Сочетания без повторений, 10 - Сочетания с повторениями
const err = 'ERROR';
const sep = 45; //separator - разделитель для длиннных чисел(br)

window.onload = function(){
	// alert(mult(30, 10));

	$.post(
			'script.php',
			{
				action: "start"
			}, function(){}
		);

	$('#close').click(function(){
		$('#window').css('margin-top', '-1000px');
		$('#main').css('margin-top', '125px');
	});

	$('#translate').click(function(){
		var k, act, res, number = $("#number").val();
		var flag = 0; //флаг для разбивки строки(br)
		var s1 = '', s2 = '';

		switch(window.flag){
			case 0:  	//факториал 
				res = fact(number);
				operation = "Факториал";
				break;
			case 1: 	//супер факториал
				res = super_fact(number);
				operation = "Супер факториал";
				break;
			case 2: 	//гипер факториал
				res = hyper_fact(number);
				operation = "Гипер факториал";
				break;
			case 3: 	//примориал
				res = primorial(number);
				operation = "Примориал";
				break;
			case 4: 	//Убывающий факториал
				k = $("#k").val();
				res = des_fact(number, k);
				operation = "Убывающий факториал";
				number = '(' + number + ')' + k;
				break;
			case 5: 	//Возрастающий факториал
				k = $("#k").val();
				res = inc_fact(number, k);
				operation = "Возрастающий факториал";
				number = k + '(' + number + ')';
				break;
			case 6: 	//Размещения с повторениями
				k = $("#k").val();
				res = embed_rep(number, k);
				operation = "Размещения с повторениями";
				number = 'A из ' + number + ' по ' + k;
				// number = k + '(' + number + ')';
				break;
			case 7: 	//Размещения без повторений
				k = $("#k").val();
				res = embed(number, k);
				operation = "Размещения без повторений";
				number = 'A из ' + number + ' по ' + k;
				// number = k + '(' + number + ')';
				break;
			case 8: 	//Перестановки без повторений
				res = perm_rep(number);
				operation = "Перестановки без повторений";
				number = number;
				break;
			case 9: 	//Сочетания без повторений
				k = $("#k").val();
				res = comb(number, k);
				operation = "Сочетания без повторений";
				number = 'C из ' + number + ' по ' + k;
				// number = k + '(' + number + ')';
				break;
			case 10: 	//Сочетания с повторениями
				k = $("#k").val();
				res = comb_rep(number, k);
				operation = "Сочетания с повторениями";
				number = 'C из ' + number + ' по ' + k;
				// number = k + '(' + number + ')';
				break;
		};
		if(res == err){
			alert("Ошибка. Данные введены некорректно.");
			$("#number").val('');
			return;
		};

		// res = String(res);

		//Сохранение в буфер результатов
		$.post(
			'script.php',
			{
				action: "save",
				number: number,
				operation: operation, 	//математическая операция
				res: res
			}, function(res){
				console.log(res);
			}
		);

		for (var i = res.length; i >= 0; i=i-3) {
			s1 = res.substring(0, i);
			s2 = res.substring(i, res.length);
			res = s1 + ' ' + s2;
		};
		
		$("#res").html(res);
	});

	$('#save').click(function(){
		act = "save_and_exit";
		$.post(
			'script.php',
			{
				action: act
			}, function(res){
				if(res == 'success'){
					alert("Результат успешно сохранён в файл history.txt");
					window.close();
				};
			}
		);
	});

	$("#cancel").click(function() {
		$("#end_content").css('top', '-1000px');
		$("#end").css('top', '-1000px');
	});

	$("#exit").click(function() {
		window.close();
	});

	$("#save_and_exit").click(function() {
		act = "save_and_exit";
		$.post(
			'script.php',
			{
				action: act
			}, function(res){
				if(res == 'success'){
					alert("Результат успешно сохранён в файл history.txt");
					window.close();
				};
			}
		);
	});
};

$(document).mousemove(function(e) {
		if(e.pageY <= 3){
			$("#end_content").css('top', '100px');
			$("#end").css('top', '0');
		};
	});

//===================================================================================================================
	//Сочетания с повторениями.
	//Входные данные: n, k - заданные числа.
	//Выходные данные: Сочетания с повторениями.
	function comb_rep(n, k) {
		if(foolproof_simbol(n) || foolproof_simbol(k))
			return err;
		var x = new BigNumber(fact(+n + +k - 1));
		var b = fact(n-1);
		var c = fact(k);
		// n = n+k-1;
		// console.log(+n + +k - 1);
		console.log('c = ' + c);
		x = x.divide(b);
		// console.log(x.divide(c).toString());
		return(x.divide(c).toString());
	};

//===================================================================================================================
	//Сочетания без повторений.
	//Входные данные: n, k - заданные числа.
	//Выходные данные: Сочетания без повторений.
	function comb(n, k) {
		if((+k > +n) || (foolproof_simbol(n)) || foolproof_simbol(k))
			return err;
		var x = new BigNumber(fact(n));
		var b = fact(n-k);
		var c = fact(k);
		x = x.divide(b);
		return(x.divide(c).toString());
	};

//===================================================================================================================
	//Перестановки без повторений
	//Входные данные: n - заданное число.
	//Выходные данные: Перестановки без повторений.
	function perm_rep(n) {
		if(foolproof_simbol(n))
			return err;
		return(fact(n));
	};

//===================================================================================================================
	//Размещения без повторений.
	//Входные данные: n, r - заданные числа.
	//Выходные данные: Размещения без повторений.
	function embed(n, r) {
		if((+k > +n) || (foolproof_simbol(n)) || foolproof_simbol(r))
			return err;
		var x = new BigNumber(fact(n));
		var b = fact(n-r);
		return(x.divide(b).toString());
	};

//===================================================================================================================
	//Размещения с повторениями.
	//Входные данные: n, r - заданные числа.
	//Выходные данные: Размещения с повторениями.
	function embed_rep(n, r) {
		if((foolproof_simbol(n)) || foolproof_simbol(r))
			return err;
		var x = new BigNumber(n);
		for (var i = 1; i < r; i++){
			x = x.multiply(n);
		};
		return(x.toString());
	};

//===================================================================================================================
	//Убывающий факториал числа.
	//Входные данные: n - заданное число.
	//Выходные данные: Убывающий факториал числа n.
	function des_fact(n, k) {
		return((fact(+n)/fact(+n - +k)).toString());
	};

//===================================================================================================================
	//Возрастающий факториал числа.
	//Входные данные: n - заданное число.
	//Выходные данные: Возрастающий факториал числа n.
	function inc_fact(n, k) {
		console.log(fact(+n + +k));
		var x = new BigNumber(fact(+n + +k-1));
		var y = new BigNumber(fact(+n-1));
		return(x.divide(y).toString());
	};

//===================================================================================================================
	//Примориал числа.
	//Входные данные: n - заданное число.
	//Выходные данные: Примориал числа n.
	function primorial(n){
		//Решето Эратосфена
		// шаг 1
		var arr = [];

		for (var i = 2; i <= n; i++)
		  arr[i] = true;

		// шаг 2
		var p = 2;	//Первое простое число

		do {
		// шаг 3
			for (i = 2 * p; i <= n; i += p)
				arr[i] = false;

		// шаг 4
			for (i = p + 1; i <= n; i++)
				if (arr[i]) break;

			p = i;
		} while (p * p <= n); // шаг 5

		//(готово). В массиве arr находятся простые числа до n

		var x = new BigNumber("1");
		var y;
		var i = 1;

		if((+n <= 0) || (foolproof_simbol(n)))
			return err;

		if(+n == 1)
			return 1;
		if(+n != 1)
			while(i <= n){
				i++;
				if(!arr[i])
					continue;
				y = new BigNumber(i.toString());
				x = x.multiply(y);
			};
		return(x.toString());
	};

//===================================================================================================================
	//Гиперфакториал числа.
	//Входные данные: a - заданное число.
	//Выходные данные: Гиперфакториал числа a.
	function hyper_fact(a){
		var x = new BigNumber("1");
		var y;
		if((+a < 0) || (foolproof_simbol(a)))
			return err;

		if((+a == 0) || (+a == 1))
			return 1;
		if(+a != 1)
			for (var i = 1; i <= +a; i++){
				y = new BigNumber(super_fact(i));
				x = x.multiply(y);
				console.log(x.toString());
			};
		console.log(x);
		return(x.toString());
			// return(fact(a)*fact);
		// 	return((x.multiply(fact(a-1))).toString());
	};

//===================================================================================================================
	//Супер факториал числа.
	//Входные данные: a - заданное число.
	//Выходные данные: супер факториал числа a.
	function super_fact(a){
		var x = new BigNumber("1");
		var y;
		if((+a < 0) || (foolproof_simbol(a)))
			return err;

		if((+a == 0) || (+a == 1))
			return 1;
		if(+a != 1)
			for (var i = 1; i <= +a; i++){
				y = new BigNumber(fact(i));
				x = x.multiply(y);
				console.log(x.toString());
			};
		console.log(x);
		return(x.toString());
			// return(fact(a)*fact);
		// 	return((x.multiply(fact(a-1))).toString());
	};

//===================================================================================================================
	//Факториал числа
	//Входные данные: a - заданное число.
	//Выходные данные: факториал числа a.
	function fact(a){
		var x = new BigNumber(a);
		if((+a < 0) || (foolproof_simbol(a)))
			return err;
		if((+a == 0) || (+a == 1))
			return 1;
		if(+a != 1)
			return((x.multiply(fact(a-1))).toString());
	};

//===================================================================================================================
	//Ф-ия проверки числа на наличие символов.
	//Входные данные: str - исходная строка
	//Выходные данные: 0 - все хорошо; 1 - ошибка.
		function foolproof_simbol(str){
			var rep = /[-\.;":'a-zA-Zа-яА-Я]/;
			if(rep.test(str))
				return 1;
			return 0;
		};


		function swat_system(){
			// alert($("#system").val());
			$("#res").html("");
			$("#number").val("");
			$("#k").val("");
			var system = $("#system").val();
			switch(system){
				case "Факториал":
					$("#formula").html("<p>n!=1*2*...*n</p>");
					switch_to_norm();
					window.flag = 0;
					break;
				case "Суперфакториал":
					$("#formula").html("<p>Sf(n)=1!*2!*...*n!</p>");
					switch_to_norm();
					window.flag = 1;
					break;
				case "Гиперфакториал":
					$("#formula").html("<p>hf(n)=Sf(1)*Sf(2)*...*Sf(n)</p>");
					switch_to_norm();
					window.flag = 2;
					break;
				case "Примориал":
					$("#formula").html("<p>#n=2*3*5*7*...*p, где p<=n</p>");
					switch_to_norm();
					window.flag = 3;
					break;
				case "Убывающий факториал":
					$("#formula").html("<p>(n)<sub>k</sub>=n!/(n-k)!</p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 4;
					break;
				case "Возрастающий факториал":
					$("#formula").html("<p>n<sup>(k)</sup>=(n+k-1)!/(n-1)!</p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 5;
					break;
				case "Размещения с повторениями":
					$("#formula").html("<p>A<sub>n</sub><sup>k</sup>=n<sup>k</sup></p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 6;
					break;
				case "Размещения без повторений":
					$("#formula").html("<p>A<sub>n</sub><sup>k</sup>=n!/(n-k)!</p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 7;
					break;
				case "Перестановки без повторений":
					$("#formula").html("<p>P<sub>n</sub>=n!</p>");
					switch_to_norm();
					window.flag = 8;
					break;
				case "Сочетания без повторений":
					$("#formula").html("<p>C<sub>n</sub><sup>k</sup>=n!/(k!*(n-k)!)</p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 9;
					break;
				case "Сочетания с повторениями":
					$("#formula").html("<p>C<sub>n</sub><sup>k</sup>=(n-1+k)!/(k!*(n-1)!)</p>");
					switch_to_norm();
					switch_to_des();
					window.flag = 10;
					break;
			};
			// if(window.flag){
			// 	$('#inputs').html('<input id="numenator" class="fraction" type="text"><br><hr width="71px" align="left" color="black"><input id="denomenator" class="fraction" type="text">');
			// 	window.flag = 0;
			// } else {
			// 	$('#inputs').html('<input type="text" id="fraction" placeholder="Например: \'1 2 3 4\'">');
			// 	window.flag = 1;
			// };
			// $('#res').html('');
		};

//===================================================================================================================
	//Ф-ия редактирования html-кода при переключении на убывающий факториал
	function switch_to_des(){
		$("#str_before").after("<tr id = \"new_str\"><td><p style=\"margin-left: 30px;\">k = </p></td><td id=\"inputs\"><input id=\"k\" class=\"fraction\" type=\"text\"><br></td></tr>");
		$("#num").html("n = ");
		$("#num").css("margin-left", "30px");
	};

//===================================================================================================================
	//Ф-ия возвращения html-кода в обычный режим.
	function switch_to_norm(){
		$("#num").css("margin-left", "0px");
		$("#num").html("Число:");
		$("#new_str").remove();
	};