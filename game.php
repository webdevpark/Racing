<?php
	require_once ('mysql.php');

	error_reporting(E_ALL);
	
	$db = new mysql();
	
	/*
	$db->hostname = "alton.snhosting.dk";
	$db->username = "goosypetscom";
	$db->password = "wRRnu8Gk";
	$db->database = "goosypetscom";
	*/

	$db->hostname = "localhost";
	$db->username = "root";
	$db->password = "123";
	$db->database = "goosypetscom";
	
	$db->connect();
	$db->selectDb('goosypetscom');
	
//	print_r($_GET);
	$action = $_GET['a'];
//	echo "-------------".$action;
	
	switch($action) {
		case 's': // save score
			$arrName = $_GET['n'];
			$arrScore = $_GET['s'];
			clearScore($db);
			
			$name = explode("&", $arrName);
			$score = explode("&", $arrScore);
			for ($i=0; $i<count($name); $i++) {
				saveScore($db, $name[$i], $score[$i]);
			}
			break;
		case 'l': // get top 10 score
			getTopList($db);
			break;
	}
	
	
	function saveScore($db, $name, $score) {
		$name = strtoupper($name);
		$query = $db->insert('racing', array('name', 'score'), array("'$name'", $score));
		$db->simpleQuery($query);
		getTopList($db);
	}
	
	function getTopList($db) {
		$query = "SELECT * FROM racing ORDER BY score DESC LIMIT 0, 10";
		$db->simpleQuery($query);
		$scoreArray = $db->result_array();
		$strValues = "[";
		$sep = "";
		foreach($scoreArray as $score) {
			$strValues .= "$sep{name: '$score->name', score: '$score->score'}";
			$sep = ",";
		}
		
		$strValues .= "]";
		echo $strValues;
	}

	function clearScore($db) {
		$query = "DELETE FROM racing";
		$db->simpleQuery($query);
	}
