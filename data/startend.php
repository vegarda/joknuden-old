<?php
$what = isset($_GET['w']) ? $_GET['w'] : null;
$amount = isset($_GET['a']) ? $_GET['a'] : null;

//echo $what;
//echo $amount;

if (isset($what)){
    if ($what == "today" || $what == "yesterday"){
        $start = strtotime($what);
        if ($what == "yesterday"){
            $end = strtotime("today");   
        }
    }
    // how many days, weeks, months?
    else if ($amount > 0){
        $start = strtotime("tomorrow - ".$amount." ".$what."s") - 86400;
    }
}
else{
    $start = strtotime("today");
}

$end = isset($end) ? $end : strtotime("tomorrow");
?>