window.onload = function() {
	var act = 'get_names';
	$.post(
		'autorization.php',
		{action: act},
		function(a){
			console.log(a);
			var name = new Array();
			var id = new Array();
			arr = a.split('@');
			name = arr[0];
			id = arr[1];
			// console.log(name);
			// console.log(id);

			name = name.split('%');
			id = id.split('?');
			
			$('#choice').after('<select id="choice_school" id_school="'+id[0]+'"></select>');
			// $('#choice_school').html('<option>school</option>');
			for (var i = 0; i <= name.length-1; i++) {
				$('#choice_school').append('<option id="' + id[i] + '">' + name[i] + '</option>');
				// $('#autorization').append('<script type="text/javascript">$(\'#'+id[i]+'\').click(function(){$(\'#choice_school\').attr(\'id_school\', \''+id[i]+'\');})</script>');
			};
		}
	)
	$('#entrance').click(function(){
		var login = $('#login').val();
		var password = $('#password').val();
		// if login == ''
		if ((login == '') || (password == '')) {
			// alert('Данные не введены');
			if (login == '') {
				$('#login').css('border','1px solid red');
			};

			if (password == '') {
				$('#password').css('border','1px solid red');
			};
			return;
		};
		var who = $('#choice').val();
		var school = $('#choice_school').val();
		// var id_school = $('#choice_school').val().attr('id');
		// console.log(login+' '+password+' '+who+' '+school);
		// console.log(school);
		// if (who == 'Преподаватель') {
		// 	alert('ok');
		// 	who = 'teachers';
		// } else if (who == 'Ученик') {
		// 	who = 'students';
		// }
		// console.log(who);
		// alert(who);
		act = 'log_in';
		$.post(
			'autorization.php',
			{
				action: act,
				school: school,
				who: who,
				login: login,
				password: password
			},
			function(a){
				console.log(a);
				if (a.indexOf('В данной школе') != -1) {
					alert(a);
					return;
				};
				// alert(a);
				document.location.href = a;
			}
		)
	});

	// function change(){
	// 	// id = $(this).attr('id');
	// 	alert('ok');
	// 	// $('#choice_school').attr('id_school', id);
	// };

	$('option').click(function(){
		// alert('123');
	})
}

