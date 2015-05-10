<?php

session_start();

require "inc/password.php";
require "inc/functions.php";
require "inc/connection.php";

requireSSL();

if (isset($_POST["submit"])){
	if ($_POST["submit"] == "1"){
		$username = $_POST["username"];
		$password = $_POST["password"];
		$errors = array();
		
		if (empty($username)){
			array_push($errors, "Username field is empty");
		}
		
		if (empty($password)){
			array_push($errors, "Password field is empty");
		}
		
		if (empty($errors)){
			//Encrypt password to check in database
			$encPassword = password_hash($password, PASSWORD_BCRYPT);
			
			$sqlStr = "SELECT * FROM tblUsers WHERE username = '".$username."'";
			
			$result = $conn->query($sqlStr);

			if ($result->num_rows > 0) {
  			  	// Valid details
				$row = $result->fetch_assoc();
				
				if (password_verify($password, $row["password"])){
					$_SESSION["userid"] = trim($row["userID"]);
				
					echo json_encode("success");
					
				} else {
					echo json_encode("no password match");
				}
			} else {
				echo json_encode("no username match");
			}
			
		} else{
			//Output errors
			echo json_encode($errors);
		}
	}
} else {
	//HTML Form 
	
	if (isset($_SESSION["userid"])){
		echo $_SESSION["userid"];
	}?>
	<form action="" name="loginForm" method="POST">
		Username: <input type="text" name="username" /><br/>
		Password: <input type="password" name="password" /><br/>
		<input type="hidden" name="submit" value="1"/>
		<input type="submit" value="Submit" />
	</form>
	<?php
}

?>