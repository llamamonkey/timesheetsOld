<?php

function requireSSL(){
	if($_SERVER["HTTPS"] != "on" && $_SERVER["HTTPS"] != "1" && $_SERVER["HTTPS"] != 1)
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
}
?>