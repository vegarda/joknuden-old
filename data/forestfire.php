<?php

header('Content-type: text/xml; charset=utf-8');

$forecast_url = 'http://api.yr.no/weatherapi/forestfireindex/1.1/';
$session = curl_init();

curl_setopt($session, CURLOPT_URL, $forecast_url);
curl_setopt($session, CURLOPT_HTTPHEADER, array(
	'Accept: text/xml'
));

curl_exec($session);

/*if(curl_errno($session)){
	esessiono 'Curl error: ' . curl_error($session);
}*/

curl_close($session);
    
?>