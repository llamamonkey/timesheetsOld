<?php

$servername = getenv("OPENSHIFT_MYSQL_DB_HOST");
$username = getenv("OPENSHIFT_MYSQL_DB_USERNAME");
$password = getenv("OPENSHIFT_MYSQL_DB_PASSWORD");
$dbname = "timesheets";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
	
?>