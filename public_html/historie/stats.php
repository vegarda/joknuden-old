<?php
mysql_connect("joknuden.no", "weewx", "joknuden6250") or die(mysql_error()); 
mysql_select_db("stats") or die(mysql_error()); 
$temperature_q = mysql_query("SELECT * FROM outTemp ORDER BY dateTime DESC LIMIT 10") or die(mysql_error()); 
$dewpoint_q = mysql_query("SELECT * FROM dewpoint ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());
$humidity_q = mysql_query("SELECT * FROM outHumidity ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());
$precipitation_q = mysql_query("SELECT * FROM rain ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());
$wind_q = mysql_query("SELECT * FROM wind ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());
$pressure_q = mysql_query("SELECT * FROM barometer ORDER BY dateTime DESC LIMIT 10") or die(mysql_error());

$temperature = mysql_fetch_array($temperature_q) or die(mysql_error()); 
$dewpoint = mysql_fetch_array($dewpoint_q) or die(mysql_error()); 
$humidity = mysql_fetch_array($humidity_q) or die(mysql_error());
$precipitation = mysql_fetch_array($precipitation_q) or die(mysql_error());
$wind = mysql_fetch_array($wind_q) or die(mysql_error());
$pressure = mysql_fetch_array($pressure_q) or die(mysql_error());

echo '
<table>
	<thead>
	<tr>
		<th colspan="2">temp</th>
		<th colspan="2">dewpoint</th>
		<th colspan="2">humidity</th>
		<th colspan="2">precipitation</th>
		<th colspan="2">wind</th>
		<th colspan="2">pressure_Q</th>
	</tr>
	<tr>
		<th>high</th>
		<th>low</th>
		<th>high</th>
		<th>low</th>
		<th>high</th>
		<th>low</th>
		<th>high</th>
		<th>low</th>
		<th>high</th>
		<th>low</th>
		<th>high</th>
		<th>low</th>
	</tr>
</thead>';
for ($i = 0; $i<=10; $i++){
	
}
?>