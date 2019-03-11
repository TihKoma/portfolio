<?php
	include "model.php";
	startup();

	if(isset($_POST['action']) && $_POST['action'] == "search_street" && isset($_POST['street']))
		echo find_street();

	if(isset($_POST['action']) && $_POST['action'] == "search_place_job" && isset($_POST['place_job']))
		echo find_place_job();

	if(isset($_POST['action']) && $_POST['action'] == "search_position" && isset($_POST['position']))
		echo find_position();




	function find_street() {
		$street = '\'%'.$_POST['street'].'%\'';
		$res = "";

		$q = mysql_query("SELECT * FROM street WHERE street LIKE $street");

		if($q != null)
			while($row = mysql_fetch_assoc($q))
				$res .= $row['street'].";";
		$res = substr($res,0,-1);
		return $res;
	};


	function find_place_job() {
		$place_job = '\'%'.$_POST['place_job'].'%\'';
		$res = "";

		$q = mysql_query("SELECT * FROM job WHERE place_job LIKE $place_job");

		if($q != null)
			while($row = mysql_fetch_assoc($q))
				$res .= $row['place_job'].";";
		$res = substr($res,0,-1);
		return $res;
	};


	function find_position() {
		$position = '\'%'.$_POST['position'].'%\'';
		$res = "";

		$q = mysql_query("SELECT * FROM job WHERE position LIKE $position");

		if($q != null)
			while($row = mysql_fetch_assoc($q))
				$res .= $row['position'].";";
		$res = substr($res,0,-1);
		return $res;
	};

?>