window.onload = function(){
	// var copyEmailBtn = document.querySelector('#link');  
	// copyEmailBtn.addEventListener('click', function(event) {  
	// 	// Выборка ссылки
	// 	if($("#link").html() == ''){
	// 		alert('Недоступно');
	// 		return;
	// 	};

	// 	var emailLink = document.querySelector('#link');  
	// 	var range = document.createRange();  
	// 	range.selectNode(emailLink);  
	// 	window.getSelection().addRange(range);  
		  
	// 	try {  
	// 		// Теперь, когда мы выбрали текст ссылки, выполним команду копирования
	// 		var successful = document.execCommand('copy');  
	// 		var msg = successful ? 'successful' : 'unsuccessful';  
	// 		console.log('Copy email command was ' + msg);
	// 		alert('Скопировано');
	// 	} catch(err) {  
	// 		console.log('Oops, unable to copy');  
	// 	}  
		 
	// 	// Снятие выделения - ВНИМАНИЕ: вы должны использовать
	// 	// removeRange(range) когда это возможно
	// 	window.getSelection().removeAllRanges();  
	// });

	// $("#link").click(function() {
	// 	CopyToClipboard("link");
	// });

	// function CopyToClipboard(containerid) {
	// 	if (document.selection) { 
	// 	    var range = document.body.createTextRange();
	// 	    range.moveToElementText(document.getElementById(containerid));
	// 	    range.select().createTextRange();
	// 	    document.execCommand("Copy"); 

	// 	} else if (window.getSelection) {
	// 	    var range = document.createRange();
	// 	     range.selectNode(document.getElementById(containerid));
	// 	     window.getSelection().addRange(range);
	// 	     document.execCommand("Copy");
	// 	     alert("Скопировано");
	// 	}
	// };


	$("#activate").click(function(){
		activate();
	});


	$("#change_inform").click(function(){
		window.location.href = "update.inform.php";
	});

	$("#update_password").click(function(){
		window.location.href = "update.password.php";
	});

	$("#output_money").click(function(){
		window.location.href = "output.money.php";
	});
};


function activate(){
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
		'script.account.php',
		{
			action: "activate",
			time: time
		}, function(res) {
			if(+res == 1){
				window.location.href = "start.php";
			} else
				alert(res);
		}
	);
};