<?php
$date= $_REQUEST['date'];
$region= $_REQUEST['region'];
$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("SELECT * FROM schedule WHERE sched_date='$date' AND sched_region='$region'", $ascent);
$rows = mysql_num_rows($result);
$count=0;
if($rows==0)
{
echo "<b>No activities found for the selected date or region</b>";
}
else
{
	echo" 
	<div id='schedule' class='12u'>
	
 <label for='idSession'>Select Activity</label>";
echo "<select class='text' id='idSession' name='idSession'>"; 
echo "<option value=''></option>";
echo "<option value='all'>All</option>";
while ($row = mysql_fetch_assoc($result)){
	$id= $row['sched_id'];
	$name=$row['sched_activity'];
	$time=$row['sched_time'];
	$region=$row['sched_region'];
	$result8 = mysql_query("SELECT * FROM region WHERE region_id='$region'", $ascent);
$rows8 = mysql_fetch_assoc($result8);
$region_name = $rows8['region_name'];
	$result1 = mysql_query("SELECT * FROM feedbacks WHERE sched_id='$id'", $ascent);
$rows1 = mysql_num_rows($result1);
if($rows1>=1)
{$count++;
echo "<option value=$id>$name ($time)</option>";	
}

}
if($count==0){
	echo "<option value=''>No Feedbacks Available</option>";
	}
echo "</select>";
	echo"</div>";
	
	
		
}
mysql_close($ascent);
 ?>