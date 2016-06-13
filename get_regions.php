<?php

$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("SELECT * FROM region", $ascent);
$rows = mysql_num_rows($result);

	echo" 
	<div id='regions' class='12u'>
	
 <label for='idregion' style='color:white'>Select Activity</label>";
echo "<select class='text' id='idregion' name='idregion' onChange='get_activities();'>"; 
echo "<option value=''></option>";
while ($row = mysql_fetch_assoc($result)){
	$id= $row['region_id'];
	$name=$row['region_name'];
	
echo "<option value=$id>($region_name) $name ($time)</option>";	
	
		
}
mysql_close($ascent);
 ?>