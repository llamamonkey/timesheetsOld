<?php

session_start();

require "inc/functions.php";
require "inc/connection.php";

if (isset($_SESSION["userID"])){
	$sqlStr = "SELECT * FROM tblUsers WHERE userID = '" . $_SESSION["userID"];
		
	$result = $conn->query($sqlStr);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		echo json_encode($row);
	}
} else {
	echo json_encode("Not logged in");
}


?>