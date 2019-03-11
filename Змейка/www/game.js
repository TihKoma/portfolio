function Game() {
	// this.time = 0;
	
}

Game.prototype.Save = function() {
	// $('body').css('backgroundColor', 'blue');

		$('#save_game').animate(
			{top: '+=400'},
			1000
		);
	
	$('#cancel').click(function() {
		$('#save_game').animate(
			{top: '-155'},
			500
		);
		$('.background').removeClass('background');
	});

	$('#save').click(function() {
		$('#save_game').css('top', '-1550px');
		$('.background').removeClass('background');
		alert($('#name').val());
		// $.post(
		// 	"save.php",
		// 	{name: $('#name').val()},
		// 	"html"
		// );
	});
	this.play();
}

Game.prototype.play = function() {
	var square = new Square(m1, 1, 2, 'right');
	square.create();
	square.generateAim();
	setInterval(function() { square.move(); }, 300);

	document.onkeydown = function(e) {
		
		switch (event.keyCode) {
			case 37:
				square.course = 'left';
				break;
			case 38:
				square.course = 'top';
				break;
			case 39:
				square.course = 'right';
				break;
			case 40:
				square.course = 'bottom';
				break;
		}
	}
}