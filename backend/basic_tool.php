<?php

ini_set('display_errors', 'On');
function get_ip()
{
	global $_SERVER;
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	return $onlineipmatches[0];
}


function get_date()
{
	date_default_timezone_set("Asia/Taipei");
	return date("Y-m-d");
}

function format_date_time($date,$dateFormat)
{		
	return date($dateFormat,strtotime($date));
}

?>