<?php

function startup()
{
	// Настройки подключения к БД.
	$hostname = 'mysql16.000webhost.com'; 
	$username = 'a8038601_root'; 
	$password = '1qaz2wsx';
	$dbName = 'a8038601_journal';
	
	// Языковая настройка.
	setlocale(LC_ALL, 'ru_RU.CP1251');	
	
	// Подключение к БД.
	mysql_connect($hostname, $username, $password) or die('No connect with data base'); 
	mysql_query('SET NAMES utf8');
	mysql_select_db($dbName) or die('No data base');

	// Открытие сессии.
	// session_start();		
}

function Select($query)
{
	$result = mysql_query($query);
		
	if (!$result)
		die(mysql_error());
		
	$n = mysql_num_rows($result);
	$arr = array();
	
	for($i = 0; $i < $n; $i++)
	{
		$row = mysql_fetch_assoc($result);		
		$arr[] = $row;
	}

	return $arr;				
}

function Insert($table, $object)
{			
	$columns = array();
	$values = array();
	
	foreach ($object as $key => $value)
	{
		$key = mysql_real_escape_string($key . '');
		$columns[] = $key;
		
		if ($value === null)
		{
			$values[] = 'NULL';
		}
		else
		{
			$value = mysql_real_escape_string($value . '');							
			$values[] = "'$value'";
		}
	}
		
	$columns_s = implode(',', $columns);
	$values_s = implode(',', $values);
			
	$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
	$result = mysql_query($query);
								
	if (!$result)
		die(mysql_error());
			
	return mysql_insert_id();
}