<?php
	
ini_set('display_errors',1);  
error_reporting(E_ALL);

session_start();

require "inc/functions.php";
require "inc/connection.php";

if (isset($_GET["day"])){
	$currDay = $_GET["day"];
} else {
	$currDay = date("Y-m-d");
}

if (isset($_GET['postKey']) || isset($_SESSION["userID"])){
	//User information has been provided
	$userid = 0;
	
	//Get userid if post key is not provided
	if (isset($_SESSION["userID"])){
		$userid = $_SESSION["userID"];
	} else{
		$sqlStr = "SELECT * FROM tblUsers WHERE postKey = '" . $_GET['postKey'] . "'";
		
		$result = $conn->query($sqlStr);

		if ($result->num_rows > 0) {
		  	// Valid details
			$row = $result->fetch_assoc();
			$userid = $row["userID"];
		} else {
			echo "Post Key didn't match a user";
		}
	}
	
	if ($userid != 0){
		//Check if current day exists, if not create a new one
		$sqlStr = "SELECT * FROM tblTime WHERE userID = '" . $userid . "' AND date = '" . $currDay . "'";
		
		$result = $conn->query($sqlStr);

		if ($result->num_rows > 0) {
		  	// Day exists
			$row = $result->fetch_assoc();
			
			$timeID = $row["timeID"];
			
			$dayUpWhere = "";
			
			if (isset($_GET["startTime"])){
				$dayUpWhere = " startTime = '" . formatTime($_GET["startTime"], isset($_GET["postKey"])) . "'";
			}
			
			if (isset($_GET["endTime"])){
				if ($dayUpWhere == ""){
					$dayUpWhere = " endTime = '" . formatTime($_GET["endTime"], isset($_GET["postKey"])) . "'";
				} else {
					$dayUpWhere .= ", endTime = '" . formatTime($_GET["endTime"], isset($_GET["postKey"])) . "'";
				}				
			}
			
			$sqlStr = "UPDATE tblTime SET " . $dayUpWhere . "WHERE timeID = " . $timeID;
			
			if ($conn->query($sqlStr) === TRUE) {
 			   echo json_encode("success");
			} else {
    			echo json_encode($conn->error);
			}
		} else {
			//Day doesn't exists so create a new one'
		  	// Day exists
			$dayInsField = "";
			$dayInsVal = "";
			
			if (isset($_GET["startTime"])){
				$dayInsField .= ", startTime" ;
				$dayInsVal .= ", '" . formatTime($_GET["startTime"], isset($_GET["postKey"])) . "'";
			}
			
			if (isset($_GET["endTime"])){
				$dayInsField .= ", endTime '";
				$dayInsVal .= ", '" . formatTime($_GET["endTime"], isset($_GET["postKey"])) . "'";
			}
			
			$sqlStr = "INSERT INTO tblTime (userID, date".$dayInsField.") VALUES ('".$userid."', '".$currDay."'".$dayInsVal.")";
			echo $sqlStr;
			if ($conn->query($sqlStr) === TRUE) {
 			   echo json_encode("success");
			} else {
    			echo json_encode($conn->error);
			}
		}
		
	}
} else {
	echo "No user information provided";
}

?>