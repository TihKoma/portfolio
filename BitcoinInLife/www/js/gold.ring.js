window.onload = function(){

	$("#link_gold_ring_10").click(function(){
		window.location.href = "gold.ring.10.php";
	});

	$("#link_gold_ring_30").click(function(){
		window.location.href = "gold.ring.30.php";
	});

	$("#link_gold_ring_50").click(function(){
		window.location.href = "gold.ring.50.php";
	});



	$("#button_10").click(function(){
		activate_gold_ring(10);
	});

	$("#button_30").click(function(){
		activate_gold_ring(30);
	});

	$("#button_50").click(function(){
		activate_gold_ring(50);
	});

};

function activate_gold_ring(segment){
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
		'script.gold.ring.php',
		{
			action: "activate",
			segment: segment,
			time: time
		}, function(res) {
			if(+res == 1){
				window.location.href = "gold.ring."+segment+".php";
			} else
				alert(res);
		}
	);
};