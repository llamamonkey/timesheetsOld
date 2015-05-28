<?php

session_start();

require "inc/functions.php";
require "inc/connection.php";

if (isset($_SESSION["userID"])){
	$sqlStr = "SELECT * FROM tblUsers WHERE userID = '" . $_SESSION["userID"];
		
	$result = $conn->query($sqlStr);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$json_array = array();
		
		$json_array["userID"] = $row["userID"];
		$json_array["username"] = $row["username"];
		$json_array["email"] = $row["email"];
		$json_array["holiday"] = $row["holiday"];
		
		echo json_encode($json_array);
	} else {
		echo json_encode("no match");
	}
} else {
	echo json_encode("Not logged in");
}


?>