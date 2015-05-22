<?php

function requireSSL(){
	if($_SERVER["HTTPS"] != "on" && $_SERVER["HTTPS"] != "1" && $_SERVER["HTTPS"] != 1)
	{
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
}

function formatTime($inpTime){
	$proTime = str_replace(".", ":", $inpTime);
	
	$timeParts = explode(":", $proTime);
	
	if (count($timeParts) > 1){
		if (intval($timeParts[0]) < 10){
			$timeParts[0] = $timeParts[0]."0";
		}
		
		if (intval($timeParts[1]) < 10){
			$timeParts[1] = $timeParts[1]."0";
		}
		
		$proTime = $timeParts[1].":".$timeParts[2].":00";
	} else {
		$proTime = "00:00:00";
	}
	
	return $proTime;
}
?>