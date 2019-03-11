window.tr_id = 0;

window.onload = function(){
	$('#myTabs a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	})

	$(".tr_list_table").click(function(){
		var id = $(this).attr("tr_id");
		view_panel(id);
	});

	$(".tr_search_table").click(function(){
		// alert("ok");
		// var id = $(this).attr("tr_search_id");
		// window.tr_search_id = id;
		// view_panel(id);
	});

	$("#save_change").click(function(){
		save_change(window.tr_id);
	});

	$("#delete").click(function(){
		delete_record(window.tr_id);
	});

	$("#btn_add").click(function(){
		if(check())
			$("#btn_add").button('loading');
	});

};

// document.onkeypress = function(e) {
// 	var a = getChar(e);
// 	alert(a);
// };

function search(e) {
	// var str = getChar(e);
	if((e.keyCode < 48 || e.keyCode > 90) && e.keyCode != 8)
		return false;

	$("#div_load_search").addClass("loading");

	var str = $('#search_word').val();
	// console.log(str);
	$.post(
		"../script.php",
		{word: str},
		function(result){
			// $("#par1").html(result);
			// console.log(result);
	 		$("#search_table").empty();
			$("#search_table").append(result);
			$("#div_load_search").removeClass("loading");
		}
	);
};

function getChar(event) {
  if (event.which == null) { // IE
    if (event.keyCode < 32) return null; // спец. символ
    return String.fromCharCode(event.keyCode)
  }

  if (event.which != 0 && event.charCode != 0) { // все кроме IE
    if (event.which < 32) return null; // спец. символ
    return String.fromCharCode(event.which); // остальные
  }

  return null; // спец. символ
}


function view_panel(id){
	window.tr_id = id;
	// $(this).button('loading');
	$("#div_load").addClass("loading");
	$("#div_load_search").addClass("loading");

	$.post(
		"../script.panel.php",
		{
			action: "get_record",
			id: id
		},
		function(result){
			// $("#par1").html(result);
			// console.log(result);
	 		$("#panel_search_table").empty();
			$("#panel_search_table").append(result);
			$("#myModal").modal('show');
			$("#div_load").removeClass("loading");
			$("#div_load_search").removeClass("loading");
		}
	);
	
};

function save_change(id) {
	var $btn = $("#save_change").button('loading');

	var surname = $("#surname").val();
	var name = $("#name").val();
	var patronymic = $("#patronymic").val();
	var sex = $("#sex").val();
	var street = $("#street").val();
	var house = $("#house").val();
	var building = $("#building").val();
	var room = $("#room").val();
	var phone = $("#phone").val();
	var email = $("#email").val();
	var place_job = $("#place_job").val();
	var position = $("#position").val();
	console.log(id+surname+name+patronymic+sex+street+house+building+room+phone+email+place_job+position);

	sex = (sex == "Мужской") ? 0 : 1;

	$.post(
		"../script.panel.php",
		{
			action: "save_change",
			id: id,
			surname: surname,
			name: name,
			patronymic: patronymic,
			sex: sex,
			street: street,
			house: house,
			building: building,
			room: room,
			phone: phone,
			email: email,
			place_job: place_job,
			position: position
		},
		function(result){
			$("#myModal").modal('hide');

			var mes = (result == 0) ? "Изменения внесены" : "Ошибка";
			$("#save_change").button('reset');
			alert(mes);
			window.location.href = window.location.href;
		}
	);
};

function delete_record(id) {
	$("#delete").button('loading');

	$.post(
		"../script.panel.php",
		{
			action: "delete",
			id: id
		},
		function(result){
			$("#myModal").modal('hide');

			var mes = (result == 0) ? "Запись удалена" : "Ошибка";
			$("#delete").button('reset');
			alert(mes);
			window.location.href = window.location.href;
		}
	);
};

function check(){
	var surname = $("#surname_add").val();
	var name = $("#name_add").val();
	var patronymic = $("#patronymic_add").val();
	var sex = $("#sex_add").val();
	var street = $("#street_add").val();
	var house = $("#house_add").val();
	var building = $("#building_add").val();
	var room = $("#room_add").val();
	var phone = $("#phone_add").val();
	var email = $("#email_add").val();
	var place_job = $("#place_job_add").val();
	var position = $("#position_add").val();

	// alert(phone);

	if(surname == '' || name == '' || patronymic == '' || sex == '' || street == '' || house == '' || building == '' || room == '' || phone == '' || email == '' || place_job == '' || position == '')
		return 0;
	return 1;
};

function search_street(e) {
	// var str = getChar(e);
	if((e.keyCode < 48 || e.keyCode > 90) && e.keyCode != 8)
		return false;

	var street = $('#street_add').val();
	console.log(street);
	$.post(
		"../script.autocomplete.php",
		{
			action: "search_street",
			street: street
		},
		function(result){
			var availableTags = Array();
			var buf = Array();
			buf = result.split(';');
			for (var i = 0; i < buf.length; i++) {
				availableTags[i] = buf[i];
			};
			console.log(availableTags);
			$("#street_add").autocomplete({source: availableTags});
		}
	);
};


function search_position(e) {
	// var str = getChar(e);
	if((e.keyCode < 48 || e.keyCode > 90) && e.keyCode != 8)
		return false;

	var position = $('#position_add').val();
	$.post(
		"../script.autocomplete.php",
		{
			action: "search_position",
			position: position
		},
		function(result){
			var availableTags = Array();
			var buf = Array();
			buf = result.split(';');
			for (var i = 0; i < buf.length; i++) {
				availableTags[i] = buf[i];
			};
			console.log(availableTags);
			$("#position_add").autocomplete({source: availableTags});
		}
	);
};

function search_place_job(e) {
	// var str = getChar(e);
	if((e.keyCode < 48 || e.keyCode > 90) && e.keyCode != 8)
		return false;

	var place_job = $('#place_job_add').val();
	$.post(
		"../script.autocomplete.php",
		{
			action: "search_place_job",
			place_job: place_job
		},
		function(result){
			var availableTags = Array();
			var buf = Array();
			buf = result.split(';');
			for (var i = 0; i < buf.length; i++) {
				availableTags[i] = buf[i];
			};
			console.log(availableTags);
			$("#place_job_add").autocomplete({source: availableTags});
		}
	);
};