<?php

header("host: ".$host);
	
$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 

$realtime_query = mysqli_query($joknuden, 
"SELECT *
FROM weewx.raw 
ORDER BY dateTime DESC
LIMIT 1");

$realtime = mysqli_fetch_assoc($realtime_query);

echo json_encode($realtime);


?>