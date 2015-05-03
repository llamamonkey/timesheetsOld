<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "inc/password.php";
require "inc/functions.php";

requireSSL();

if (isset($_POST["submit"])){
	if ($_POST["submit"] == "1"){
		$username = $_POST["username"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		$errors = array();
		
		if (empty($username)){
			array_push($errors, "Username field is empty");
		}
		
		if (empty($email)){
			array_push($errors, "Email field is empty");
		}
		
		if ($email != $_POST["emailVerify"]){
			array_push($errors, "Emails do not match");
		}
		
		if (filter_var($email_a, FILTER_VALIDATE_EMAIL)) {
			array_push($errors, "Email adress is not valid");
		}
		
		if (empty($email)){
			array_push($errors, "Password field is empty");
		}
		
		if ($password != $_POST["passwordVerify"]){
			array_push($errors, "Passwords do not match");
		}
		
		if (empty($errors)){
			//Enter user information into database
			$encPassword = password_hash($password, PASSWORD_BCRYPT);
			
			$sqlStr = "INSERT INTO tblUsers (username, password, email), VALUES ('".$username."', '".$encPassword."', '".$email.")";
			
			if ($conn->query($sql) === TRUE) {
 			   echo json_encode("success");
			} else {
    			echo json_encode($conn->error);
			}
			
		} else{
			//Output errors
			echo json_encode($errors);
		}
	}
} else {
	//HTML Form ?>
	<form action="" name="signupForm" method="POST">
		Username: <input type="text" name="username" /><br/>
		Email: <input type="text" name="email" /><br/>
		Email Verify: <input type="text" name="emailVerify" /><br/>
		Password: <input type="password" name="password" /><br/>
		Password Verify: <input type="password" name="passwordVerify" /><br/>
		<input type="hidden" name="submit" value="1"/>
		<input type="submit" value="Submit" />
	</form>
	<?php
}

?>