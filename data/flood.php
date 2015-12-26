<?php

header('Content-type: application/json; charset=utf-8');

$forecast_url = 'http://api01.nve.no/hydrology/forecast/flood/v1.0.3/api/WarningByMunicipality/1119/1/';
$session = curl_init();

curl_setopt($session, CURLOPT_URL, $forecast_url);
curl_setopt($session, CURLOPT_HTTPHEADER, array(
	'Accept: application/json'
));

curl_exec($session);

curl_close($session);
    
?>