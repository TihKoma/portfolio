window.flag = 0; // 0 - из римской, 1 - из арабской
	const err = 'ERROR';
	const unit = 1; 	//Идентификатор единиц
	const decade = 2; 	//Идентификатор десятков
	const hundred = 3; 	//Идентификатор сотен
	const thousand = 4; //Идентификатор тысяч

window.onload = function(){

	$('#close').click(function(){
		$('#window').css('margin-top', '-1000px');
		$('#main').css('margin-top', '125px');
	});

	$('#translate').click(function(){

		var number = $('#number').val();
		var res;
		if(number == ''){
			alert('Введите число');
			return;
		};

		if(!window.flag)
			res = convert_to_arabic(number)
		else
			res = convert_to_roman(number);

		if(res == err){
			alert('Ошибка перевода. Не верно введено число.');
			$('#number').val('');
			$('#res').html('');
		} else
			$('#res').html(res);
	});


	$('#save').click(function(){
		var act = 'save';
		var number = $('#number').val();
		var res = $('#res').html();
		// alert(number + ' ' + res);
		if((number == '') || (res == '')){
			alert('Ошибка. Введите число и нажмите "Выполнить"');
			return;
		};

		$.post(
			'script.php',
			{
				action: act,
				number: number,
				res: res
			}, function(res){
				alert('Результат успешно сохранён в файл "history.txt"');
			}
		);
	});
};

function swat_system(){
	if(!window.flag){
		$('#number')[0].setAttribute('placeholder', 'Например: 1998');
		window.flag = 1;
	} else {
		$('#number')[0].setAttribute('placeholder', 'Например: MCMXCVIII');
		window.flag = 0;
	};
};

//===================================================================================================================
			//Ф-ия перевода числа из арабской системы счисления в римскую.
			//Входные данные: input_num - исходное число
			//Выходные данные: римское число или ошибка.
			function convert_to_roman(input_num){
				var output_num = '';
				var discharge = 1;

				if(foolproof_simbol(input_num) || (+input_num > 4000) || (+input_num < 1)) //Если в числе содержаться символы, вернуть ошибку
					return err;

				if(input_num == 4000){/*!!!!!!!!!!!!!!!!!!!!!!!!!!!!!*/};
				while(input_num > 0){
					output_num = convert_discharge(input_num%10, discharge) + output_num;
					discharge++;
					input_num = input_num/10|0;
				};

				return output_num;
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

//===================================================================================================================
			//Ф-ия конвертации одного разряда числа.
			//num - цифра(0-9); discharge - номер разряда(единицы, десятки и т.д.)
			function convert_discharge(num, discharge){
				var res = '';
				if(discharge == unit)
					switch(num){
						case 0:
							break;
						case 1:
							res = 'I';
							break;
						case 2:
							res = 'II';
							break;
						case 3:
							res = 'III';
							break;
						case 4:
							res = 'IV';
							break;
						case 5:
							res = 'V';
							break;
						case 6:
							res = 'VI';
							break;
						case 7:
							res = 'VII';
							break;
						case 8:
							res = 'VIII';
							break;
						case 9:
							res = 'IX';
							break;
						default:
							return err;
					};

				if(discharge == decade)
					switch(num){
						case 0:
							break;
						case 1:
							res = 'X';
							break;
						case 2:
							res = 'XX';
							break;
						case 3:
							res = 'XXX';
							break;
						case 4:
							res = 'XL';
							break;
						case 5:
							res = 'L';
							break;
						case 6:
							res = 'LX';
							break;
						case 7:
							res = 'LXX';
							break;
						case 8:
							res = 'LXXX';
							break;
						case 9:
							res = 'XC';
							break;
						default:
							return err;
					};

				if(discharge == hundred)
					switch(num){
						case 0:
							break;
						case 1:
							res = 'C';
							break;
						case 2:
							res = 'CC';
							break;
						case 3:
							res = 'CCC';
							break;
						case 4:
							res = 'CD';
							break;
						case 5:
							res = 'D';
							break;
						case 6:
							res = 'DC';
							break;
						case 7:
							res = 'DCC';
							break;
						case 8:
							res = 'DCCC';
							break;
						case 9:
							res = 'CM';
							break;
						default:
							return err;
					};

				if(discharge == thousand)
					switch(num){
						case 0:
							break;
						case 1:
							res = 'M';
							break;
						case 2:
							res = 'MM';
							break;
						case 3:
							res = 'MMM';
							break;
						default:
							return err;
					};
				return res;
			};

//===================================================================================================================
			//Ф-ия перевода числа из римской системы счисления в арабскую
			//Входные данные: str - строка с буквами(римским числом)
			//Выходные данные: арабское число или ошибка.
			function convert_to_arabic(str){
				var res = 0;
				var flag = 0; //флаг, отвечающий за необходимость вычитания из текущего числа предыдущего.
				var prev, current, next; //предыдущее, текущее и след. число(во избежание повторных одинаковых вызовов ф-ии convert_letter)
				str = str.toUpperCase();//Перевод в верхний регистр
				for (var i = 0; i < str.length-1; i++){
					current = convert_letter(str[i]);
					next = convert_letter(str[i+1]);

					if(current < next)
						flag = 1
					else
						if(flag){
							res += current - convert_letter(str[i-1]); //текущее число минус предыдущее.
							flag = 0;
						} else
							res += current;
				};
				//Обработка последней цифры
				if(flag)
					res += convert_letter(str[i]) - convert_letter(str[i-1]);
				else
					res += convert_letter(str[i]);

				if(!foolproof(str, res)) //Проверка правильности введенных данных
					return res;
				return err;
			};

//===================================================================================================================
			//Ф-ия перевода римской буквы в арабское число
			//Входные данные: letter - буква
			//Выходные данные: число, соответствующее букве
			function convert_letter(letter){
				var res = 0;
				switch(letter){
					case 'I':
						res = 1;
						break;
					case 'V':
						res = 5;
						break;
					case 'X':
						res = 10;
						break;
					case 'L':
						res = 50;
						break;
					case 'C':
						res = 100;
						break;
					case 'D':
						res = 500;
						break;
					case 'M':
						res = 1000;
						break;
					default:
						return err;
				};
				return res;
			};
//===================================================================================================================
			//Ф-ия защиты от дурака при переводе из римской системы счисления
			//Входные данные: str - исходное римское число; res - арабское число, полученное при переводе
			//Выходные данные: 0 - все хорошо; 1 - ошибка.
			function foolproof(str, res){
				if(str == convert_to_roman(res))
					return 0;
				return 1;
			};