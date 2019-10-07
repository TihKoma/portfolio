window.experience = -1; 												// Выбранный стаж работы для сортировки. -1 - не выбран
window.sector = -1;														// Выбранный сектор. -1 - не выбран

window.onload = function () {

	$(".btn_experience").click(function () {
		clickBtnExp(this);
	});

	$(".btn_sector").click(function () {
		clickBtnSector(this);
	});
};

// Клик на параметр стажа
function clickBtnExp(obj) {
	$newExp = $(obj).attr("experience");

	if (window.experience == $newExp) 									// Если выбран уже выбранный элемент -> снимаем параметр сортировки
		removeStylesBtn(obj);											// Убрать соответствующие стили
	else																// Если выбран новый параметр
		changeStylesBtnExp(obj);										// Поменять стили кнопок

	window.experience = (window.experience == $newExp) ? -1 : $newExp; 	// Фиксация параметра

	$.post(
		"/server/sort/",
		{
			"experience": window.experience,
			"sector": window.sector
		},
		function(result) {
			if (result < 0)
				alert("Error setting post-parameters");
			$("#container_rating").empty();
			$("#container_rating").html(result);
	});
};



// Клик на параметр сектора
function clickBtnSector(obj) {
	$newSector = $(obj).attr("sector");

	if (window.sector == $newSector) 									// Если выбран уже выбранный элемент -> снимаем параметр сортировки
		removeStylesBtn(obj);											// Убрать соответствующие стили
	else																// Если выбран новый параметр
		changeStylesBtnSector(obj);										// Поменять стили кнопок

	window.sector = (window.sector == $newSector) ? -1 : $newSector; 	// Фиксация параметра

	$.post(
		"/server/sort/",
		{
			"experience": window.experience,
			"sector": window.sector
		},
		function(result) {
			if (result < 0)
				alert("Error setting post-parameters");
			$("#container_rating").empty();
			$("#container_rating").html(result);
	});
};






// Поменять стили кнопок при клике на стаж
function changeStylesBtnExp(obj) {
	$(".btn_experience").removeClass("btn_group_active");
	$(obj).addClass("btn_group_active");
};

// Снять стили кноки при повторном нажатии на один объект
function removeStylesBtn(obj) {
	$(obj).removeClass("btn_group_active");
};


// Поменять стили кнопок при клике на сектор
function changeStylesBtnSector(obj) {
	$(".btn_sector").removeClass("btn_group_active");
	$(obj).addClass("btn_group_active");
};