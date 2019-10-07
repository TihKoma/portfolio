window.onload = function () {
	$("#input").click(function () {
		// alert(12);
	});

	$("#input_before").click(function () {
		if (!checkForm())
			return;

		$(this).addClass("hidden");
		$(".info_hidden").removeClass("hidden");
	});
}

function checkForm() {
	$(".alert-danger").detach();

	var cnt = $('#skillForm').find('input[type=checkbox]:checked').length;

	var str = "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>Ошибка!</strong> Необходимо выбрать 7 навыков.</div>";
	if (cnt != 7) {
		$("#skillForm ul").after(str);
		return false;
	};

	return true;
};