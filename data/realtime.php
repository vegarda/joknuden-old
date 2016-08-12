<?php

include('config.php');
	
$joknuden = mysqli_connect($host, $user, $pass) or die(mysqli_error($joknuden)); 

$realtime_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.raw 
ORDER BY dateTime DESC
LIMIT 1");

$realtime = mysqli_fetch_assoc($realtime_query);

echo json_encode($realtime);


?>