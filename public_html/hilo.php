<?php
mysql_connect("joknuden.no", "weewx", "joknuden6250") or die(mysql_error()); 
mysql_select_db("stats") or die(mysql_error()); 
$temperature_q = mysql_query("SELECT * FROM outTemp ORDER BY dateTime DESC LIMIT 1") or die(mysql_error()); 
$dewpoint_q = mysql_query("SELECT * FROM dewpoint ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$humidity_q = mysql_query("SELECT * FROM outHumidity ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$precipitation_q = mysql_query("SELECT * FROM rain ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$wind_q = mysql_query("SELECT * FROM wind ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());
$pressure_q = mysql_query("SELECT * FROM barometer ORDER BY dateTime DESC LIMIT 1") or die(mysql_error());

$temperature = mysql_fetch_array($temperature_q) or die(mysql_error()); 
$dewpoint = mysql_fetch_array($dewpoint_q) or die(mysql_error()); 
$humidity = mysql_fetch_array($humidity_q) or die(mysql_error());
$precipitation = mysql_fetch_array($precipitation_q) or die(mysql_error());
$wind = mysql_fetch_array($wind_q) or die(mysql_error());
$pressure = mysql_fetch_array($pressure_q) or die(mysql_error());

mysql_select_db("weewx") or die(mysql_error()); 

$archive10_q = mysql_query("SELECT * FROM archive ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());

$windspeed10 = 0;
$winddir10 = 0;
$gust10 = 0;
$gust10x = 0;
$gust10y = 0;
while ($archive10 = mysql_fetch_array($archive10_q)) {
	$windspeed10 += $archive10['windSpeed'];
	$winddir10 += $archive10['windDir'];
	$gust10 += $archive10['windGust'];
	$gust10x += cos($archive10['windGustDir']*3.14/180)*$archive10['windGust'];
	$gust10y += sin($archive10['windGustDir']*3.14/180)*$archive10['windGust'];
}
$windspeed10 = $windspeed10/10;
$winddir10 = $winddir10/10;
$gust10 = $gust10/10;
$gustdir10 = atan($gust10x/$gust10y)*180/3.14/10;

$startOfDay = $temperature['dateTime'];
$WindSpeedMax_q = mysql_query("SELECT MAX(windSpeed) FROM archive WHERE dateTime > ".$startOfDay."") or die(mysql_error());
$WindSpeedMax = mysql_fetch_array($WindSpeedMax_q)['MAX(windSpeed)'] or die(mysql_error());

$windDirAvg = atan($wind['xsum']/$wind['ysum'])*180/3.14;

echo'
<div class="hilo">
<div class="hilo-temp">
	<table class="weather-summary">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>High</th>
				<th>Low</th>
				<th>Average</th>
			</tr>
		</thead>
	<tbody>
			<tr class="data-temperature">
				<td>Temperature</td>
				<td>'.round($temperature['max'], 1).' <span class="table-unit">°C</span></td>
				<td>'.round($temperature['min'], 1).' <span class="table-unit">°C</span></td>
				<td>'.round($temperature['sum']/$temperature['count'], 1).' <span class="table-unit">°C</span></td>
			</tr>
			<tr class="data-dew">
				<td>Dew Point</td>
				<td>'.round($dewpoint['max'], 1).' <span class="table-unit">°C</span></td>
				<td>'.round($dewpoint['min'], 1).' <span class="table-unit">°C</span></td>
				<td>'.round($dewpoint['sum']/$dewpoint['count'], 1).' <span class="table-unit">°C</span></td>
			</tr>
			<tr class="data-precipitation">
				<td>Humidity</td>
				<td>'.$humidity['max'].'<span class="table-unit">%</span></td>
				<td>'.$humidity['min'].'<span class="table-unit">%</span></td>
				<td>'.round($humidity['sum']/$humidity['count'], 0).'<span class="table-unit">%</span></td>
			</tr>
			<tr class="data-pressure">
				<td>Pressure</td>
				<td>'.number_format(round($pressure['max'], 1), 1, ',', ' ').' <span class="table-unit">hPa</span></td>
				<td>'.number_format(round($pressure['min'], 1), 1, ',', ' ').' <span class="table-unit">hPa</span></td>
				<td>'.number_format(round($pressure['sum']/$pressure['count'], 1), 1, ',', ' ').' <span class="table-unit">hPa</span></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="hilo-wind">
	<table class="weather-summary">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>High</th>
				<th>Last 10</th>
				<th>Average</th>
			</tr>
		</thead>
		<tbody>
			<tr class="data-speed">
				<td>Wind Speed</td>
				<td>'.number_format(round($WindSpeedMax, 1), 1).'<span class="table-unit"> m/s</span></td>
				<td>'.number_format(round($windspeed10, 1), 1).'<span class="table-unit"> m/s</span></td>
				<td>'.number_format(round($wind['sum']/$wind['count'], 1), 1).'<span class="table-unit"> m/s</span></td>
			</tr>
			<tr class="data-gust">
				<td>Wind Gust</td>
				<td>'.number_format(round($wind['max'], 1), 1).'<span class="table-unit"> m/s</span><span class="table-unit"> ('.$wind['gustdir'].'°)</span></td>
				<td>'.number_format(round($gust10, 1), 1).'<span class="table-unit"> m/s</span><span class="table-unit"> ('.round($gustdir10, 0).'°)</span></td>
				<td></td>
			</tr>
			<tr class="data-direction">
				<td>Wind Direction</td>
				<td></td>
				<td>'.round($winddir10, 0).'<span class="table-unit"> °</span></td>
				<td>'.round($windDirAvg, 0).'<span class="table-unit"> °</span></td>
			</tr>
			<tr>
				<td>Precipitation</td>
				<td>'.round($precipitation['sum'], 1).' <span class="table-unit"> mm</span></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
</div>';

?>