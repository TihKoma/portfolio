var def_val = 100/cnt;
var mas_val = Array(cnt); // Массив значений ползунков

function slider(mas, current, curr_val) {

	var delta = mas[current] - curr_val; 	// Разница между прошлым и текущим значением текущего ползунка
	var inc = delta/(mas.length-1); 		// Общий инкремент всех ползунков


	var sum = 0, cnt = mas.length;


	// Инкрементируем все ползунки (кроме текущего)
	for (var i = 0; i < mas.length; i++) {
		if (i == current)
			continue;
		mas[i] += inc;
	};

	mas[current] = curr_val; // текущий ползунок

	// Обработка выхода ползунков за пределы нуля (<0)
	// sum - суммарное значение выхода ползунков за пределы (далее распределяем sum между доступными* ползунками)
	// cnt - кол-во доступных* ползунков
	// * - доступным считается ползунков, значение которого > 0
	for (var i = 0; i < mas.length; i++)
		if (mas[i] < 0) {
			sum += mas[i];
			mas[i] = 0;
			cnt--;
		};

	return distribute(mas, sum, cnt, current);
};


// Распределение sum по "доступным" ползункам
function distribute(mas, sum, cnt, current) {
	var inc = sum/(cnt-1); // общий инкремент

	for (var i = 0; i < mas.length; i++) {
		if (i == current || mas[i] <= 0)
			continue;
		mas[i] += inc;
	};

	var sum = get_sum(mas);
	console.log('|' + sum + '|');
	mas[get_max(mas)] += 100 - sum;
	
	sum = get_sum(mas);
	console.log('|' + sum + '|');

	return mas;
};

function set_values(mas, current) {
	console.log(mas);
	for (var i = 0; i < mas.length; i++) {
		$("#input_polzunok"+i).val(mas[i]);
		if (i == current)
			continue;
  		$("#polzunok"+i).slider("option", "value", mas[i]);	
	};
};

function get_sum(mas) {
	var sum = 0;
	for (var i = 0; i < mas.length; i++)
		sum += mas[i];
	return sum;
};

function get_max(mas) {
	var max = 0;
	for (var i = 0; i < mas.length; i++)
		if (mas[i] > mas[max])
			max = i;
	return max;
};