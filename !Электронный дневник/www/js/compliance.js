window.onload = function() {
	$('.add').click(function(){
		var id = +$(this).attr('id');
		console.log(id);
		if (id >= 11) return;
		$(this).before('<select class="select"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option></select>');
		id += 1;
		$(this).attr('id', id);
		if (id == 11) $(this).empty();	
	});
}
// 	var classes = new Array();
// 	var count;
// 	var act = 'compliance';
// 	for (var i = 1; i <= count_teachers; i++) {
// 		count = $('#'+login_teachers[i]).attr('count');
// 		// console.log(count);
// 		classes[i] = '';
// 		for (var j = 1; j <= count; j++) {
// 			classes[i] = classes[i] + $("#"+login_teachers[i]+"_select_"+j).val()+'^';
// 		};
// 		classes[i] = classes[i].substring(0, classes[i].length - 1);
// 		console.log(login_teachers[i]);
// 		// console.log(classes[i]);