<?php

$hilostart = microtime(true);

include('config.php');

$joknuden = mysqli_connect($host, $user, $pass) or die(mysql_error());

include('startend.php');

$temperatureMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_outTemp WHERE min=(SELECT MIN(min) FROM weewx.archive_day_outTemp WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$temperatureMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_outTemp WHERE max=(SELECT MAX(max) FROM weewx.archive_day_outTemp WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$dewpointMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_dewpoint WHERE min=(SELECT MIN(min) FROM weewx.archive_day_dewpoint WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$dewpointMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_dewpoint WHERE max=(SELECT MAX(max) FROM weewx.archive_day_dewpoint WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$humidityMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_outHumidity WHERE min=(SELECT MIN(min) FROM weewx.archive_day_outHumidity WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$humidityMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_outHumidity WHERE max=(SELECT MAX(max) FROM weewx.archive_day_outHumidity WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$precipitationMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_rain WHERE min=(SELECT MIN(min) FROM weewx.archive_day_rain WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$precipitationMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_rain WHERE max=(SELECT MAX(max) FROM weewx.archive_day_rain WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_wind WHERE min=(SELECT MIN(min) FROM weewx.archive_day_wind WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_wind WHERE max=(SELECT MAX(max) FROM weewx.archive_day_wind WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$barometerMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_barometer WHERE min=(SELECT MIN(min) FROM weewx.archive_day_barometer WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$barometerMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive_day_barometer WHERE max=(SELECT MAX(max) FROM weewx.archive_day_barometer WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());

$avg_q = mysqli_query($joknuden, "SELECT dateTime, AVG(outTemp) temperature, AVG(barometer) barometer, AVG(outHumidity) humidity, AVG(dewpoint) dewpoint FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC") or die(mysqli_error());
$avg = mysqli_fetch_array($avg_q) or die(mysqli_error()); 

$temperatureMin = mysqli_fetch_array($temperatureMin_q) or die(mysqli_error()); 
$temperatureMax = mysqli_fetch_array($temperatureMax_q) or die(mysqli_error()); 
$dewpointMin = mysqli_fetch_array($dewpointMin_q) or die(mysqli_error()); 
$dewpointMax = mysqli_fetch_array($dewpointMax_q) or die(mysqli_error()); 
$humidityMin = mysqli_fetch_array($humidityMin_q) or die(mysqli_error()); 
$humidityMax = mysqli_fetch_array($humidityMax_q) or die(mysqli_error()); 
$precipitationMin = mysqli_fetch_array($precipitationMin_q) or die(mysqli_error()); 
$precipitationMax = mysqli_fetch_array($precipitationMax_q) or die(mysqli_error()); 
$windMin = mysqli_fetch_array($windMin_q) or die(mysqli_error()); 
$windMax = mysqli_fetch_array($windMax_q) or die(mysqli_error()); 
$barometerMin = mysqli_fetch_array($barometerMin_q) or die(mysqli_error()); 
$barometerMax = mysqli_fetch_array($barometerMax_q) or die(mysqli_error()); 

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

$windSpeedMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windSpeed=(SELECT MAX(windSpeed) FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windSpeedMax = mysqli_fetch_array($windSpeedMax_q) or die(mysqli_error());
$windSpeedMaxDir = $windSpeedMax['windDir'];
$windSpeedMaxTime = $windSpeedMax['dateTime'];
$windSpeedMax = $windSpeedMax['windSpeed'];

#$windSpeedMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windSpeed=(SELECT min(windSpeed) FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
#$windSpeedMin = mysqli_fetch_array($windSpeedMin_q) or die(mysqli_error());	
#$windSpeedMinDir = $windSpeedMin['windDir'];
#$windSpeedMinTime = $windSpeedMin['dateTime'];
#$windSpeedMin = $windSpeedMin['windSpeed'];

$windGustMax_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windGust=(SELECT MAX(windGust) FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windGustMax = mysqli_fetch_array($windGustMax_q) or die(mysqli_error());
$windGustMaxDir = $windGustMax['windGustDir'];
$windGustMaxTime = $windGustMax['dateTime'];
$windGustMax = $windGustMax['windGust'];

$windGustMin_q = mysqli_query($joknuden, "SELECT * FROM weewx.archive WHERE windGust=(SELECT MIN(windGust) FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC) ORDER BY dateTime DESC LIMIT 1") or die(mysqli_error());
$windGustMin = mysqli_fetch_array($windGustMin_q) or die(mysqli_error());
$windGustMinDir = $windGustMin['windGustDir'];
$windGustMinTime = $windGustMin['dateTime'];
$windGustMin = $windGustMin['windGust'];

$windSpeedAvg_q = mysqli_query($joknuden, "SELECT AVG(windSpeed) FROM weewx.archive WHERE dateTime >= ".$start." ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$windSpeedAvg = mysqli_fetch_array($windSpeedAvg_q)['AVG(windSpeed)'] or die(mysql_error());

$windGustAvg_q = mysqli_query($joknuden, "SELECT AVG(windGust) FROM weewx.archive WHERE dateTime >= ".$start	." ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$windGustAvg = mysqli_fetch_array($windGustAvg_q)['AVG(windGust)'] or die(mysqli_error());


//$windDirAvg = minusTo360(atan($wind['xsum']/$wind['ysum'])*180/3.14);
$windDirAvg = "-";

$what = strtoupper($what);
#$amount = intval($amount) > 1 ? $amount." " : "1 ";

function hiloTime($what, $amount, $time){
	if ($what == "YESTERDAY" || ($what == "DAY" && $amount == 1)){
		return date("H:i", $time);
	}
	else{
		return date("Y-m-d H:i", $time);
	}	
}

echo '
        <div class="container-fluid">
            <div class="row hilo-container">
                <div class="col-md-8 col-md-offset-2 hilo-temp">
                    <table class="table table-condensed hilo">
                      <tr>
                        <th class="icon"></th>
                        <th colspan="3" style="border-left: 1px solid #ddd;">'.$amount." ".$what.' HIGH</th>
                        <th colspan="3" style="border-left: 1px solid #ddd;">'.$amount." ".$what.' LOW</th>
                        <th colspan="2" style="border-left: 1px solid #ddd;">'.$amount." ".$what.' AVG</th>
                      </tr>
                      <tr>
                        <td><i class="wi wi-thermometer"></i></td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.round($temperatureMax['max'], 1).'°C</td>
                        <td>'.hiloTime($what, $amount, $temperatureMax['maxtime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.round($temperatureMin['min'], 1).'°C</td>
                        <td>'.hiloTime($what, $amount, $temperatureMin['mintime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.round($avg['temperature'], 1).'°C</td>
                      </tr>
                      <tr>
                        <td><i class="wi wi-barometer"></i></td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.number_format(round($barometerMax['max'], 1), 1, '.', '').' hPa</td>
                        <td>'.hiloTime($what, $amount, $barometerMax['maxtime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.number_format(round($barometerMin['min'], 1), 1, '.', '').' hPa</td>
                        <td>'.hiloTime($what, $amount, $barometerMin['mintime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.number_format(round($avg['barometer'], 1), 1, '.', '').' hPa</td>
                      </tr>
                      <tr>
                        <td><i class="wi wi-humidity"></i></td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.$humidityMax['max'].'%</td>
                        <td>'.hiloTime($what, $amount, $humidityMax['maxtime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.$humidityMin['min'].'%</td>
                        <td>'.hiloTime($what, $amount, $humidityMin['mintime']).'</td>
                        <td colspan="2" style="border-left: 1px solid #ddd;">'.round($avg['humidity'], 0).'%</td>
                      </tr>
					  
                      <tr>
                        <td><i class="wi wi-windy"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windSpeedMax, 1), 1).' m/s</td>
                        <td>'.round($windSpeedMaxDir, 0).'°</td>
                        <td>'.hiloTime($what, $amount, $windSpeedMaxTime).'</td>
                        <td style="border-left: 1px solid #ddd;">0 m/s</td>
                        <td>-</td>
                        <td>-</td>
                        <td style="border-left: 1px solid #ddd;">'.round($windSpeedAvg, 1).' m/s</td>
                        <td>'.round($windDirAvg, 0).'°</td>
                      </tr>
                      <tr>
                        <td><i class="wi wi-strong-wind"></i></td>
                        <td style="border-left: 1px solid #ddd;">'.number_format(round($windGustMax, 1), 1).' m/s</td>
                        <td>'.round($windGustMaxDir, 0).'°</td>
                        <td>'.hiloTime($what, $amount, $windGustMaxTime).'</td>
                        <td style="border-left: 1px solid #ddd;">0 m/s</td>
                        <td>-</td>
                        <td>-</td>
                        <td style="border-left: 1px solid #ddd;">'.round($windGustAvg, 1).' m/s</td>
                        <td>-</td>
                      </tr>    
                    </table>

                </div>
            </div>
        </div>';

$hiloend = microtime(true);

echo '<!--- hilo.php time: '.($hiloend-$hilostart).'s --->';

?>