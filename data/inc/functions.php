<?php

function requireSSL(){
	if($_SERVER["HTTPS"] != "on" && $_SERVER["HTTPS"] != "1" && $_SERVER["HTTPS"] != 1)
	{
		//header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		//exit();
	}
}

function formatTime($inpTime, $round = false){
	$proTime = str_replace(".", ":", $inpTime);
	
	$timeParts = explode(":", $proTime);
	
	if (count($timeParts) > 1){
		
		if ($round){
			$intMins = intval($timeParts[1]);
			
			$intMins = round($intMins/5) * 5;
			
			$timeParts[1] = strval($intMins);
		}
		
		if  (intval($timeParts[1]) >= 60){
			$timeParts[0] = strval(intval($timeParts[0]) + 1);
			
			$timeParts[1] = strval(intval($timeParts[1]) - 60);
		}
		
		if (intval($timeParts[0]) < 10){
			$timeParts[0] = "0".$timeParts[0];
		}
		
		if (intval($timeParts[1]) < 10){
			$timeParts[1] = "0".$timeParts[1];
		}
		
		$proTime = $timeParts[0].":".$timeParts[1].":00";
	} else {
		$proTime = "00:00:00";
	}
	
	return $proTime;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
?>
