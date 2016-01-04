<?php

$what_array = array('yesterday', 'day', 'week', 'month', 'ytd', 'year');

$what = isset($_GET['what']) ? $_GET['what'] : "day";

if (in_array($what, $what_array)){

	$amount = isset($_GET['amount']) ? intval($_GET['amount']) : 1;
	$amount = $amount > 0 ? $amount : 1;

	if ($what == "yesterday"){
		$start = strtotime($what);
		$end = strtotime("today");   
	}else if ($what == "ytd"){
		$start = strtotime(date("Y")."-01-01 00:00:00");
	}
	else{
		$start = strtotime("tomorrow - ".$amount." ".$what."s");
	}

	$end = isset($end) ? $end : strtotime("tomorrow");

}
?>