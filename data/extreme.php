<?php
include('host.php');
include('config.php');
include('startend.php');
if(is_integer($start) && (is_integer($end))){
    
    $zero4d = array('max'=>0,'maxtime'=>0,'min'=>0,'mintime'=>0);
    $measurements = array('barometer', 'outTemp', 'outHumidity');//, 'rain', 'rainRate');
    $units = array('barometer'=>$zero4d, 'outTemp'=>$zero4d, 'outHumidity'=>$zero4d);//, 'rain'=>$zero4d, 'rainRate'=>$zero4d);
    
	$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error()); 
    foreach (array('max', 'min') as $maxOrMin){
        foreach ($measurements as $measurement){
            $query = mysqli_query($joknuden, 
            "SELECT ".$maxOrMin."time, ".$maxOrMin."
            FROM weewx_archive_".$measurement."
            WHERE ".$maxOrMin." = (SELECT ".$maxOrMin."(".$maxOrMin.") FROM stats.".$measurement." WHERE dateTime >= ".$start."
            AND dateTime < ".$end."
            ORDER BY dateTime DESC)
            AND  dateTime >= ".$start."
            AND dateTime < ".$end."
            ORDER BY dateTime DESC");

            //while ($row = mysqli_fetch_assoc($query)){
            $row = mysqli_fetch_assoc($query);
            $units[$measurement][$maxOrMin] = $row[$maxOrMin];
            $units[$measurement][$maxOrMin.'time'] = $row[$maxOrMin.'time'];
            //}
        }
    }  

    echo json_encode($units,  JSON_NUMERIC_CHECK);
             
}
?>