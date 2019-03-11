//
// Конструктор матрицы.
//
function Matrix(containerId, rows, cols)
{
	//this = Object.create(Matrix.prototype);
	// в первом приближении this = {}; (при пустом prototype)
	// может вернуть любой объект, если указать сие напрямую
	// id контейнера
	this.containerId = containerId;
	
	// число строк
	this.rows = rows;
	
	// число столбцов
	this.cols = cols;
	//this.rocks = [ [1,2], [3,4] ];
}

Matrix.prototype.create = function() {
	var matrix = document.getElementById(this.containerId);
	var n = this.rows * this.cols;	
	
	matrix.innerHTML = '';
	
	for (var i = 0; i < n; i++)
	{
		var div = document.createElement('div');
		div.className = 'cell';
		matrix.appendChild(div);
	}
}

Matrix.prototype.getCell = function(row, col) {
	// todo
	var elem = document.getElementById('matrix1').children[(row - 1)*20 + col - 1];
	if (elem.className == 'cell on') {
		return true;
	} else {
		return false;
	};
}

Matrix.prototype.setCell = function(row, col, val) {
	var ind = (row - 1) * this.cols + col - 1;
	var matrix = document.getElementById(this.containerId);
	var cell = matrix.children[ind];	
	cell.className = (val ? 'cell on' : 'cell');
}
		
