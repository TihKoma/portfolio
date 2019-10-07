<?php
	// Основной роутер

	include_once "config.php";
	include_once "model/m_Base.php";
	include_once "model/m_MYSQL.php";
	include_once "model/m_Page.php";
	include "controller/c.page.php";

	$method = "";
	$params = [];

	$url = parse_url("http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);	// Получаем текущий полный URL
	$dirs = explode('/', $url['path']);											// Распетрушиваем путь на «папки»

	isset($url['query']) && parse_str($url['query'], $_GET);					// Парсим переменные GET в глобальный массив $_GET

	for ($i = 1; $i < (count($dirs)-1); $i++)									// Декодируем в UTF-8 все символы, отличные от латиницы
		$dirs[$i] = urldecode($dirs[$i]);

	$Page = Page::getInstance();												// Получаем экземпляр класса (Singleton)

	$Page->getMethod($dirs, $method, $params);
	$Page->$method($params);















	// $Page->startup();

	// $User->startup();


	// // Получаем текущий полный URL
	// $url = parse_url("http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);

	// // Распетрушиваем путь на «папки»
	// $dirs = explode('/', $url['path']);

	// var_dump($dirs);

	// // Парсим переменные GET в глобальный массив $_GET
	// isset($url['query']) && parse_str($url['query'], $_GET);

	// // Декодируем в UTF-8 все символы, отличные от латиницы
	// for ($i=1; $i<(count($dirs)-1); $i++) {
	// $dirs[$i]=urldecode($dirs[$i]);
	// }

	// // Выводим содержимое массива $dirs
	// for ($i=0; $i<=(count($dirs)-1); $i++) {
	// echo '$dirs['.$i.']='.$dirs[$i].'<'.'br />';
	// }
	// print_r($_GET);

	// $action = (isset($_GET['act'])) ? $_GET['act'] : null;

	// switch ($action) {
	// 	case 'register':
	// 		// $controller = new Page();
	// 		echo $Page->view_register(); // Получить форму для создания задачи
	// 		break;

	// 	case "reg_save_info":
	// 		// $controller = new User();
	// 		$User->reg_save_info();
	// 		break;

	// 	case "choice_skills":
	// 		// $controller = new Page();
	// 		echo $Page->print_choice_skills();
	// 		break;

	// 	case "reg_save_skills":
	// 		// $controller = new User();
	// 		$User->reg_save_skills();
	// 		break;

	// 	case "percent_distribution":
	// 		// $controller = new Page();
	// 		echo $Page->print_percent_distribution();
	// 		break;

	// 	case "reg_save_percent":
	// 		// $controller = new User();
	// 		$User->reg_save_percent();
	// 		break;

	// 	case "rating":
	// 		// $controller = new Page();
	// 		echo $Page->print_rating();
	// 		break;
		
	// 	default:
	// 		// $controller = new Page();
	// 	 	echo $Page->view_index(); // Стартовая страница
	// };











?>