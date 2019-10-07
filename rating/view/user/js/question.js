window.onload = function () {

};

function checkAnswer() {
	$(".alert-danger").detach();

	var cnt = $('#answerForm').find('input[type=radio]:checked').length;

	var str = "<div class=\"alert alert-danger alert-dismissible fade in\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>Выберите вариант ответа.</div>";
	if (cnt != 1) {
		$("#answerForm ul").after(str);
		return false;
	};

};