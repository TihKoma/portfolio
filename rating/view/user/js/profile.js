function stepOver(event) {
	$(event).addClass("circle_active");
};

function stepOut(event) {
	$(event).removeClass("circle_active");
};

function checkInputs() {
	$(".alert-danger").detach();

	// var cnt = $('#answerForm').find('input[type=radio]:checked').length;

	var surname = $("#input_surname").val();
	var name = $("#input_name").val();
	var patronymic = $("#input_patronymic").val();
	var position = $("#input_position").val();
	var company = $("#input_company").val();

	var str = "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Заполните все поля.</div>";
	if (surname == '' || name == '' || patronymic == '' || position == '' || company == '') {
		$("#picture").after(str);
		return false;
	};

};