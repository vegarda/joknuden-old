<?php

$joknuden = mysqli_connect("joknuden.no", "weewx", "joknuden6250") or die(mysql_error()); 
$temperature_q = mysqli_query($joknuden, "SELECT * FROM stats.outTemp ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error()); 
$dewpoint_q = mysqli_query($joknuden, "SELECT * FROM stats.dewpoint ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$humidity_q = mysqli_query($joknuden, "SELECT * FROM stats.outHumidity ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$precipitation_q = mysqli_query($joknuden, "SELECT * FROM stats.rain ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$wind_q = mysqli_query($joknuden, "SELECT * FROM stats.wind ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$pressure_q = mysqli_query($joknuden, "SELECT * FROM stats.barometer ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());

$temperature = mysqli_fetch_array($temperature_q) or die(mysqli_error()); 
$dewpoint = mysqli_fetch_array($dewpoint_q) or die(mysqli_error()); 
$humidity = mysqli_fetch_array($humidity_q) or die(mysqli_error());
$precipitation = mysqli_fetch_array($precipitation_q) or die(mysqli_error());
$wind = mysqli_fetch_array($wind_q) or die(mysqli_error());
$pressure = mysqli_fetch_array($pressure_q) or die(mysqli_error());

$raw10_q = mysqli_query($joknuden, "SELECT * FROM weewx.raw ORDER BY dateTime DESC LIMIT 240") or die(mysqli_error());

$windspeed10 = 0;
$wind10x = 0;
$wind10y = 0;
$windCount = 0;
$gust10 = 0;
$gust10x = 0;
$gust10y = 0;
$gustCount = 0;
while ($raw10 = mysqli_fetch_array($raw10_q)){
	if ($raw10['windSpeed'] != NULL & $raw10['windDir'] != NULL){
		$windspeed10 += $raw10['windSpeed'];
		$wind10x += cos($raw10['windDir']*3.14/180)*$raw10['windSpeed'];
		$wind10y += sin($raw10['windDir']*3.14/180)*$raw10['windSpeed'];
		$windCount++;
	}
	if ($raw10['windGust'] != NULL & $raw10['windGustDir'] != NULL){
		$gust10 += $raw10['windGust'];
		$gust10x += cos($raw10['windGustDir']*3.14/180)*$raw10['windGust'];
		$gust10y += sin($raw10['windGustDir']*3.14/180)*$raw10['windGust'];
		$gustCount++;
	}
}
    
if ($windCount > 0){
	$windspeed10 = $windspeed10/$windCount;
	$gust10 = $gust10/$gustCount;
    $gustdir10 = minusTo360(atan($gust10y/$gust10x)*180/3.14);
    $winddir10 = minusTo360(atan($wind10y/$wind10x)*180/3.14);
}
else {
	$windspeed10 = 0;
	$gust10 = 0;
    $gustdir10 = 0;
    $winddir10 = 0;
}
function minusTo360($degrees){
	return $degrees < 0 ? 180 + $degrees : $degrees;
}

$startOfDay = strtotime("today");

$windSpeedMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windSpeed=(SELECT MAX(windSpeed) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windSpeedMax = mysqli_fetch_array($windSpeedMax_q) or die(mysqli_error());
$windSpeedMaxDir = $windSpeedMax['windDir'];
$windSpeedMaxTime = $windSpeedMax['dateTime'];
$windSpeedMax = $windSpeedMax['windSpeed'];

$windSpeedMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windSpeed=(SELECT min(windSpeed) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windSpeedMin = mysqli_fetch_array($windSpeedMin_q) or die(mysqli_error());
$windSpeedMinDir = $windSpeedMin['windDir'];
$windSpeedMinTime = $windSpeedMin['dateTime'];
$windSpeedMin = $windSpeedMin['windSpeed'];

$windGustMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windGust=(SELECT MAX(windGust) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windGustMax = mysqli_fetch_array($windGustMax_q) or die(mysqli_error());
$windGustMaxDir = $windGustMax['windGustDir'];
$windGustMaxTime = $windGustMax['dateTime'];
$windGustMax = $windGustMax['windGust'];

$windGustMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windGust=(SELECT MIN(windGust) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windGustMin = mysqli_fetch_array($windGustMin_q) or die(mysqli_error());
$windGustMinDir = $windGustMin['windGustDir'];
$windGustMinTime = $windGustMin['dateTime'];
$windGustMin = $windGustMin['windGust'];

$windSpeedAvg_q = mysqli_query($joknuden, "SELECT AVG(windSpeed) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$windSpeedAvg = mysqli_fetch_array($windSpeedAvg_q)['AVG(windSpeed)'] or die(mysql_error());

$windGustAvg_q = mysqli_query($joknuden, "SELECT AVG(windGust) FROM weewx.archive WHERE dateTime > ".$startOfDay." ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$windGustAvg = mysqli_fetch_array($windGustAvg_q)['AVG(windGust)'] or die(mysqli_error());


$windDirAvg = minusTo360(atan($wind['xsum']/$wind['ysum'])*180/3.14);


echo '
        <div class="container-fluid">
            <div class="row hilo-container">
                <div class="col-md-6 hilo-temp">
                    <table class="table table-condensed hilo">
                      <tr>
                        <th></th>
                        <th colspan="2" style="border-left: 1px solid #ddd;">DAY MAX</th>
                        <th colspan="2" style="border-left: 1px solid #ddd;">DAY MIN</th>
                        <th style="border-left: 1px solid #ddd;">DAY AVG</th>
                      </tr>
                      <tr>
                        <td><i class="wi wi-thermometer"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.round($temperature['max'], 1).' °C</td>
                        <td>'.date("H:i", $temperature['maxtime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round(    $temperature['min'], 1).' °C</td>
                        <td>'.date("H:i", $temperature['mintime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($temperature['sum']/$temperature['count'], 1).' °C</td>
                      </tr>
                      <tr>
                        <td><i class="fa fa-signal"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($pressure['max'], 1), 1, '.', '').' hPa</td>
                        <td>'.date("H:i", $pressure['maxtime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($pressure['min'], 1), 1, '.', '').' hPa</td>
                        <td>'.date("H:i", $pressure['mintime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($pressure['sum']/$pressure['count'], 1), 1, '.', '').' hPa</td>
                      </tr>
                      <tr>
                        <td><i class="fa fa-tachometer"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.$humidity['max'].' %</td>
                        <td>'.date("H:i", $humidity['maxtime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.$humidity['min'].' %</td>
                        <td>'.date("H:i", $humidity['mintime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($humidity['sum']/$humidity['count'], 0).' %</td>
                      </tr>
                      <tr>
                        <td><i class="wi wi-thermometer-exterior"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.round($dewpoint['max'], 1).' °C</td>
                        <td>'.date("H:i", $dewpoint['maxtime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($dewpoint['min'], 1).' °C</td>
                        <td>'.date("H:i", $dewpoint['mintime']).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($dewpoint['sum']/$dewpoint['count'], 1).' °C</td>
                      </tr>
                    </table>
                </div>
                <div class="col-md-6 hilo-wind">
                    <table class="table table-condensed hilo">
                      <tr>
                        <th></th>
                        <th colspan="3" style="border-left: 1px solid #ddd;">DAY MAX</th>
                        <th colspan="3" style="border-left: 1px solid #ddd;">DAY MIN</th>
                        <th colspan="2" style="border-left: 1px solid #ddd;">10 MIN AVG</th>
                        <th colspan="2" style="border-left: 1px solid #ddd;">DAY AVG</th>
                      </tr>
                      <tr>
                        <td><i class="wi wi-windy"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windSpeedMax, 1), 1).' m/s</td>
                        <td>'.round($windSpeedMaxDir, 0).'°</td>
                        <td>'.date("H:i", $windSpeedMaxTime).'</td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windSpeedMin, 1), 1).' m/s</td>
                        <td>'.round($windSpeedMinDir, 0).'°</td>
                        <td>'.date("H:i", $windSpeedMinTime).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($windspeed10, 1).' m/s</td>
                        <td>'.round($winddir10, 0).'°</td>
                        <td style="border-left: 1px solid #ddd;">'.round($windSpeedAvg, 1).' m/s</td>
                        <td>'.round($windDirAvg, 0).'°</td>
                      </tr>
                      <tr>
                        <td><i class="wi wi-strong-wind"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windGustMax, 1), 1).' m/s</td>
                        <td>'.round($windGustMaxDir, 0).'°</td>
                        <td>'.date("H:i", $windGustMaxTime).'</td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windGustMin, 1), 1).' m/s</td>
                        <td>'.round($windGustMinDir, 0).'°</td>
                        <td>'.date("H:i", $windGustMinTime).'</td>
                        <td style="border-left: 1px solid #ddd;">'.round($gust10, 1).' m/s</td>
                        <td>'.round($gustdir10, 0).'°</td>
                        <td style="border-left: 1px solid #ddd;">'.round($windGustAvg, 1).' m/s</td>
                        <td>-</td>
                      </tr>    
                    </table>
                </div>
            </div>
        </div>';




/*echo '{
"temperature":{
	"max": '.round($temperature['max'], 1).',
	"maxtime": '.$temperature['maxtime'].',
	"min": '.round($temperature['min'], 1).',
	"mintime": '.$temperature['mintime'].',
	"avg": '.round($temperature['sum']/$temperature['count'], 1).'},

"pressure":{
	"max": '.number_format(round($pressure['max'], 1), 1, '.', '').',
	"maxtime": '.$pressure['maxtime'].',
	"min": '.number_format(round($pressure['min'], 1), 1, '.', '').',
	"mintime": '.$pressure['mintime'].',
	"avg": '.number_format(round($pressure['sum']/$pressure['count'], 1), 1, '.', '').'},

"humidity":{
	"max": '.$humidity['max'].',
	"maxtime": '.$humidity['maxtime'].',
	"min": '.$humidity['min'].',
	"mintime": '.$humidity['mintime'].',
	"avg": '.round($humidity['sum']/$humidity['count'], 0).'},

"dewpoint":{
	"max": '.round($dewpoint['max'], 1).',
	"maxtime": '.$dewpoint['maxtime'].',
	"min": '.round($dewpoint['min'], 1).',
	"mintime": '.$dewpoint['mintime'].',
	"avg": '.round($humidity['sum']/$humidity['count'], 0).'},

"wind":{
	"max": '.number_format(round($windSpeedMax, 1), 1).',
	"maxtime": null,
	"min": null,
	"mintime": '.$temperature['maxtime'].',
	"avg10": '.number_format(round($windspeed10, 1), 1).',
	"avg": '.number_format(round($wind['sum']/$wind['count'], 1), 1).'},

"gust":{
	"max": '.number_format(round($wind['max'], 1), 1).',
    "maxdir": '.$wind['gustdir'].',
	"maxtime": '.$wind['maxtime'].',
	"min": null,
	"mintime": null,
	"avg10": '.number_format(round($gust10, 1), 1).',
    "avg10dir": '.round($gustdir10, 0).',
	"avg": null,
	"avgdir": null},

"windDir":{
	"avg10": '.round($winddir10, 0).',
	"avg": '.round($windDirAvg, 0).'}
}';*/



?>