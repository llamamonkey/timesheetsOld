<?php

session_start();

require "inc/functions.php";
require "inc/connection.php";

header('Content-Type: application/json');

if (isset($_SESSION["userID"])){
	$strWhere = "";
	
	if (isset($_GET['dateFrom'])){
		$strWhere .= " AND date >= '" . $_GET['dateStart'] . "'";
	}
	
	if (isset($_GET['dateTo'])){
		$strWhere .= " AND date <= '" . $_GET['dateTo'] . "'";
	}
	
	$sqlStr = "SELECT * FROM timeDetail WHERE userID = " . $_SESSION["userID"] . $strWhere;
	
	$result = $conn->query($sqlStr);

	if ($result->num_rows > 0) {
		
		$json_array = array();
		
		while ($row = $result->fetch_assoc()){
			array_push($json_array, $row);
		}
		
		echo json_encode($json_array);
	} else {
		echo json_encode("no match");
	}
} else {
	echo json_encode("Not logged in");
}


?>