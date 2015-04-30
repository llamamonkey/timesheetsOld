<?php

require "inc/password.php";
require "inc/functions.php";

requireSSL();

if (ISSET($_POST["submit"])){
	if ($_POST["submit"] == "1"){
		$username = $_POST["username"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		$errors = array();
		
		if (empty($email)){
			array_push($errors, "Email field is empty");
		}
		
		if ($email != $_POST["emailVerify"]){
			array_push($errors, "Emails do not match");
		}
		
		if (empty($email)){
			array_push($password, "Password field is empty");
		}
		
		if ($password != $_POST["passwordVerify"]){
			array_push($errors, "Passwords do not match");
		}
		
		if (empty($errors)){
			//Enter user information into database
		}
	}
}

?>