<?php
session_start();

$_SESSION['what'] = isset($_GET['what']) ? $_GET['what'] : "today";
$what = $_SESSION['what'];
$_SESSION['amount'] = isset($_GET['amount']) ? $_GET['amount'] : 1;
$amount = $_SESSION['amount'];


if ($what == "today" || $what == "yesterday"){
    $start = strtotime($what);
    if ($what == "yesterday"){
        $end = strtotime("today");   
    }
}else if ($what == "ytd"){
	$start = strtotime(date("Y")."-01-01 00:00:00");
}
else{
    $start = strtotime("today - ".$amount." ".$what."s");
}

$end = isset($end) ? $end : strtotime("tomorrow");

$_SESSION['start'] = $start;
$_SESSION['end'] = $end;

?>