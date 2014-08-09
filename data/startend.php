<?php
$what = isset($_GET['w']) ? $_GET['w'] : "today";
$amount = isset($_GET['a']) ? $_GET['a'] : 1;


if ($what == "today" || $what == "yesterday"){
    $start = strtotime($what);
    if ($what == "yesterday"){
        $end = strtotime("today");   
    }
}
else{
    $start = strtotime("today - ".$amount." ".$what."s");
}

$end = isset($end) ? $end : strtotime("tomorrow");
?>