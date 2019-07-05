<?php
include "controller/c.list.php";
include "controller/c.create.php";
include "controller/c.authorization.php";
include "controller/c.edit.php";
include "model/model.php";

startup();

$action = (isset($_GET['action'])) ? $_GET['action'] : null;

switch ($action) {
	case 'taskCreate':
		$controller = new create();
		echo $controller->getForm(); // Получить форму для создания задачи
		break;

	case 'taskAdd':
		$controller = new create();
		$controller->addTask($_POST['userName'], $_POST['email'], $_POST['taskContent']); // Добавить в БД новую задачу
		break;

	case 'view_list':
		$controller = new taskList();
	 	echo $controller->view_list($_GET['page'], $COUNT, $_GET['sortBy']); // Вывести список задач
	 	break;

	case 'createFormLogin':
		$controller = new user();
		echo $controller->getFormLogin(); // Получить форму для авторизации
		break;

	case 'login':
		$controller = new user();
		$controller->login($_POST['login'], $_POST['password']); // Вход
		break;

	case 'taskEdit':
		$controller = new edit();
		echo $controller->viewEditList($_GET['page'], $COUNT); // Вывести список задач для редактирования
		break;

	case 'saveChanges':
		$controller = new edit();
		echo $controller->saveChanges($_GET['id'], $_POST['status'], $_POST['content']); // Сохранить изменения
		break;

	case 'logout':
		$controller = new user();
		$controller->logout(); // Выйти
		break;
	
	default:
		$controller = new taskList();
	 	echo $controller->view_list(1, $COUNT); // Стартовая страница
};



?>