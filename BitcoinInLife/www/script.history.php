<?php
	header("Content-type: text/html; charset=utf-8");
	include('php/model.php');

	startup();
	
	if(!isset($_POST['mas']) || !$_POST['mas'] || (!isset($_POST['count']))){
		echo "-1";
		return;
	};

	$mas = $_POST['mas'];
	$count = $_POST['count'];
	$sum = 0;

	for($i = 0; $i < count($mas); $i++){
		$id = '\''.$mas[$i].'\'';
		$q = mysql_query("DELETE FROM history WHERE id = $id");
		if($q != NULL)
			$sum += 1;
	};
	if($sum == $count)
		echo "1";
	else
		echo "Ошибка удаления";

?>