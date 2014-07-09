<?php
mysql_connect("192.168.10.10", "weewx", "joknuden6250") or die(mysql_error()); 
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

echo'
<div class="small-12 large-6 columns">
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
				<td>'.number_format($temperature['max'], 1).' <span class="table-unit">°C</span></td>
				<td>'.number_format($temperature['max'], 1).' <span class="table-unit">°C</span></td>
				<td>'.number_format($temperature['sum']/$temperature['count'], 1).' <span class="table-unit">°C</span></td>
			</tr>
			<tr class="data-dew">
				<td>Dew Point</td>
				<td>'.number_format($dewpoint['max'], 1).' <span class="table-unit">°C</span></td>
				<td>'.number_format($dewpoint['min'], 1).' <span class="table-unit">°C</span></td>
				<td>'.number_format($dewpoint['sum']/$dewpoint['count'], 1).' <span class="table-unit">°C</span></td>
			</tr>
			<tr class="data-precipitation">
				<td>Humidity</td>
				<td>'.$humidity['max'].'<span class="table-unit">%</span></td>
				<td>'.$humidity['min'].'<span class="table-unit">%</span></td>
				<td>'.number_format($humidity['sum']/$humidity['count'], 0).'<span class="table-unit">%</span></td>
			</tr>
			<tr>
				<td>Precipitation</td>
				<td>'.number_format($precipitation['sum'], 1).' <span class="table-unit">mm</span></td>
				<td>--</td>
				<td>--</td>
			</tr>
		</tbody>
	</table>
 </div>';

echo '
<div class="small-12 large-6 columns">
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
			<tr class="data-speed">
				<td>Wind Speed</td>
				<td>'.number_format($wind['max'], 1).'<span class="table-unit">m/s</span></td>
				<td>--</td>
				<td>'.number_format($wind['sum']/$wind['count'], 1).'<span class="table-unit">m/s</span></td>
			</tr>
			<tr class="data-gust">
				<td>Wind Gust</td>
				<td>'.number_format($wind['max'], 1).'<span class="table-unit">m/s</span></td>
				<td>--</td>
				<td>--</td>
			</tr>
			<tr class="data-direction">
				<td>Wind Direction</td>
				<td>--</td>
				<td>--</td>
				<td>ENE</td>
			</tr>
			<tr class="data-pressure">
				<td>Pressure</td>
				<td>'.number_format($pressure['max'], 1, ',', ' ').' <span class="table-unit">hPa</span></td>
				<td>'.number_format($pressure['min'], 1, ',', ' ').' <span class="table-unit">hPa</span></td>
				<td>--</td>
			</tr>
		</tbody>
	</table>
</div>';

?>