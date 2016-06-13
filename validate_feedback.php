<?php
$date= $_REQUEST['date'];
$region= $_REQUEST['region'];
$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("SELECT * FROM feedbacks WHERE sched_date='$date'", $ascent);
$rows = mysql_num_rows($result);

if($rows==0)
{
echo "No activities found for the selected date or region";
}
else
{
echo "done";
	
}
mysql_close($ascent);
 ?>