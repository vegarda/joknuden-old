<?php

header('Content-type: text/xml; charset=utf-8');

$forecast_url = 'http://api.met.no/weatherapi/textforecast/1.6/?forecast=gale;language=nb';
$session = curl_init();

curl_setopt($session, CURLOPT_URL, $forecast_url);
curl_setopt($session, CURLOPT_HTTPHEADER, array(
	'Accept: text/xml'
));

curl_exec($session);

curl_close($session);
    
?>