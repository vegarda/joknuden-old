<?php

/* calculate the sunset time for Lisbon, Portugal
Latitude: 38.4 North
Longitude: 9 West
Zenith ~= 90
offset: +1 GMT
*/

$now = new DateTime(date("Y-m-d"), new DateTimeZone('Europe/Oslo'));
$offset = round(date_offset_get($now)/3600);

echo date_sunrise(time(), SUNFUNCS_RET_STRING, 60, 7.5, 90, $offset);

?>