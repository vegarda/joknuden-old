<?php

include('config.php');
$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

$query = mysqli_query($joknuden, 
"SELECT dateTime, barometer, outTemp, rainRate, dayRain
FROM weewx.archive 
WHERE dateTime > ".strtotime("today")."
ORDER BY dateTime DESC
LIMIT 1");

$row = mysqli_fetch_assoc($query);

echo json_encode($row,  JSON_NUMERIC_CHECK);

?>