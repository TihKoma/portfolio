function Square(matrix, row, col, course)
{
	this.body = [row, col];
	this.course = course;
	// this.course = '';
	//matrix
	this.parent = matrix;
}

Square.prototype.create = function() {
	this.parent.setCell(this.body[0], this.body[1], true);
}

Square.prototype.move = function() {
	var last_body = this.body.slice(); //clone
		console.log(this.last_course);
	switch(this.course)
	{
		case 'right':



			if (this.body[1] < 20) {

				this.body[1]++; //Изменяем положение головы змейки

				this.validation();//Проверка на врезание змейки самой в себя
				this.body[1]--;	//Для перезаписи массива
				for (var i = this.body.length-1; i > 1; i-=2) {	//Продвижение змейки по пути
					this.body[i] = this.body[i-2];	//Циклический сдвиг
					this.body[i-1] = this.body[i-3];
				};

				this.body[1]++;

				if (this.parent.getCell(this.body[0],this.body[1])) {
					this.body.push(last_body[0], last_body[1]);
					// this.body = this.body.reverse();
					this.win();
				};
			} else {
				this.defeat();
			}
			break;
		case 'left':
			if (this.body[1] > 1) {
				this.body[1]--; //Изменяем положение головы змейки
				this.validation();//Проверка на врезание змейки самой в себя
				this.body[1]++;
				for (var i = this.body.length-1; i > 1; i-=2) {
					this.body[i] = this.body[i-2];	//Циклический сдвиг
					this.body[i-1] = this.body[i-3];
				};

				this.body[1]--;

				if (this.parent.getCell(this.body[0],this.body[1])) {
					this.body.push(last_body[0], last_body[1]);
					this.win();
				};
			} else {
				this.defeat();
			}
			break;
		case 'top':
			if (this.body[0] > 1) {
				this.body[0]--;
				this.validation();
				this.body[0]++;
				for (var i = this.body.length-2; i > 0; i-=2) {
					this.body[i] = this.body[i-2];	//Циклический сдвиг
				};

				this.body[0]--;

				for (var i = this.body.length-1; i > 1 ; i-=2) {
					this.body[i] = this.body[i-2];
				};

				if (this.parent.getCell(this.body[0],this.body[1])) {
					this.body.push(last_body[last_body.length-2], last_body[last_body.length-1]);

					this.win();
				};
			} else {
				this.defeat();
			}
			break;
		case 'bottom':
			if (this.body[0] < 20) {
				this.body[0]++;
				this.validation();
				this.body[0]--;
				for (var i = this.body.length-2; i > 1; i-=2) {
					this.body[i] = this.body[i-2];	//Циклический сдвиг
				};

				this.body[0]++;

				for (var i = this.body.length-1; i > 1 ; i-=2) {
					this.body[i] = this.body[i-2];
				};

				if (this.parent.getCell(this.body[0],this.body[1])) {
					this.body.push(last_body[0], last_body[1]);
					this.win();
				};
			} else {
				this.defeat();
			}
			break;
	}
	this.parent.setCell(last_body[last_body.length-2], last_body[last_body.length-1], false); //Затираем хвост
	for (var i = 0; i < this.body.length; i+=2) {
		this.parent.setCell(this.body[i], this.body[i+1], true);
	};
	this.last_course = this.course;
}

Square.prototype.validation = function() {
	for (var i = 2; i <= this.body.length-2; i+=2) {
		if (this.body[0] == this.body[i] && this.body[1] == this.body[i+1]) {
			this.defeat();
		};
	};
}

Square.prototype.win = function() {
	score++;
	var obj = document.getElementById('score');
	obj.innerHTML = score;
	this.generateAim();
	// for (var i = 0; i < this.body.length; i+=2) {
	// 	this.parent.setCell(this.body[i], this.body[i+1],true);
	// };

}

Square.prototype.generateX = function()
{
	var x = Math.round(Math.random() * 18 + 2);
	return x;
}

Square.prototype.generateY = function()
{
	var y = Math.round(Math.random() * 18 + 2);
	return y;
}

Square.prototype.generateAim = function() {
	aimX = this.generateX();//	aim - ЦЕЛЬ
	aimY = this.generateY();
	this.parent.setCell(aimX, aimY, true);
}

Square.prototype.defeat = function(row, col)//Проигрыш
{
	// this.parent.setCell(this.body[0], this.body[1], false);
	this.body = [1,1];
	this.course = 'right';
	
	$.post(	//Отправляем имя игрока и его очки
		"save.php",
		{score: score, name: $('#name').val()}
	);

	alert('Вы проиграли! ' + 'Ваш счёт: ' + score);
	score = 0;
	var obj = document.getElementById('score');
	obj.innerHTML = score;

	document.location.href = document.location.href;//Редирект
}