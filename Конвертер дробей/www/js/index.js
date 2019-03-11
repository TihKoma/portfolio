window.flag = 0; 		// 0 - рациональную в цепную, 1 - цепную в рациональную
const err = 'ERROR';
window.numenator = 0; 	//Числитель(значение)
window.denominator = 0; //Знаменатель(значение)
window['test'] = 'My test';

window.onload = function(){

	$('#close').click(function(){
		$('#window').css('margin-top', '-1000px');
		$('#main').css('margin-top', '125px');
	});

	$('#translate').click(function(){
		if(!window.flag)
			main_to_continued_fract()
		else
			main_to_rational_fract();
	});

	$('#save').click(function(){
		var act = 'save';
		var fraction, res;
		var numenator = $('#numenator').val();
		var denomenator = $('#denomenator').val();
	//Повторный расчет во избежание неправильного сохранения.
		if(!window.flag)								   //|
			main_to_continued_fract()					   //|
		else											   //|
			main_to_rational_fract();					   //|
	//=======================================================
		if(!window.flag){
			fraction = '(' + numenator + ')/(' + denomenator + ')';
			res = $('#res').html();
		} else {
			fraction = '[' + $('#fraction').val() + ']';
			res = '(' + $('#res_numenator').html() + ')/(' + $('#res_denominator').html() + ')';
		};

		// alert('Дробь - ' + fraction + '; Результат - ' + res);
		if((numenator == '') || (denomenator == '') || (res == ''))
			return;

		$.post(
			'script.php',
			{
				action: act,
				number: fraction,
				res: res
			}, function(res){
				alert('Результат успешно сохранён в файл "history.txt"');
			}
		);
	});
};

//===================================================================================================================
	//Ф-ия реализации перевода цепной дроби в рациональную
	//Входные данные: -
	//Выходные данные: результат на экране.
	function main_to_rational_fract(){
		var str = $('#fraction').val();
		var res;
		if(str == ''){
			alert('Ошибка. Введите дробь');
			return;
		};
		res = continued_fract_to_rational(str);
		if((res == err) || (window.denominator == 0)){
			alert('Ошибка конвертации. Не верно введено число.');
			$('#fraction').val('');
			$('#res').html('');
			return;
		};
		$('#res').html('<qwe id="res_numenator">' + window.numenator + '</qwe><br><hr width="71px" align="center" color="black"><qwe id="res_denominator">' + window.denominator + '</qwe>');
	};

//===================================================================================================================
	//Ф-ия реализации перевода рациональной дроби в цепную
	//Входные данные: -
	//Выходные данные: результат на экране.
	function main_to_continued_fract(){
		var numenator = $('#numenator').val();
		var denomenator = $('#denomenator').val();
		var res;
		if((numenator == '') || (denomenator == '')){
			alert('Ошибка. Введите дробь');
			return;
		};
		res = rational_fract_to_continued(numenator, denomenator);

		if(res == err){
			alert('Ошибка конвертации. Не верно введено число.');
			$('#numenator').val('');
			$('#denomenator').val('');
			$('#res').html('');
			return;
		};
		$('#res').html('[' + res + ']');
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

//=======================================================================================================
	//Ф-ия перевода цепной дроби в рациональную.
	//Входные данные: str - строка с цепной дробью(Например: "1 2 3 4")
	//Выходные данные: 0 - все хорошо. Числитель и знаменатель записываются в глобальные перем-ные
		function continued_fract_to_rational(str){
			if(foolproof_simbol(str)) //При наличии символов - ошибка
				return err;

			var mas = str.split(' ');
			var count = mas.length - 1;
			var x;
			var numenator = {num: 1}; 			  //Числитель
			var denominator = {num: +mas[count]}; //Знаменатель
			while(count-1 >= 0){
				numenator.num += +mas[count-1] * denominator.num;
				swap(numenator, denominator);
				count--;
			};
			swap(numenator, denominator);
			
			window.numenator = numenator.num;
			window.denominator = denominator.num;
			return 0; //Все хорошо
		};

//=======================================================================================================
	//Ф-ия перевода рациональной дроби в цепную.
	//Входные данные: a - числитель, b - знаменатель.
		function rational_fract_to_continued(a, b){
			if(foolproof_simbol(a) || foolproof_simbol(b) || (+b == 0)) //При наличии символов или (зн-ль = 0) - ошибка
				return err;

			var mas = [], count = 0;
			var numenator = {num: +a}; 		//Числитель
			var denominator = {num: +b}; 	//Знаменатель
			// if(a <= b){
			mas[count] = a/b|0;
			swap(numenator, denominator);
			count++;
			// };
			while(denominator.num % numenator.num != 0){
				mas[count] = numenator.num / denominator.num|0;//Целочисленное деление
				console.log('count = ' + count + ' a = ' + numenator.num + ' b = ' + denominator.num);
				if(numenator.num > denominator.num)
					swap(numenator, denominator);
				denominator.num %= numenator.num;
				count++;
			};
			return mas.join(',');
		};

		function swap(a, b){
			var buf = a.num;
			a.num = b.num;
			b.num = buf;
		};

		function swat_system(){
			if(window.flag){
				$('#inputs').html('<input id="numenator" class="fraction" type="text"><br><hr width="71px" align="left" color="black"><input id="denomenator" class="fraction" type="text">');
				window.flag = 0;
			} else {
				$('#inputs').html('<input type="text" id="fraction" placeholder="Например: \'1 2 3 4\'">');
				window.flag = 1;
			};
			$('#res').html('');
		};