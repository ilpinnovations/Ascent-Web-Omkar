<?php
$id= $_REQUEST['id'];

$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("SELECT * FROM schedule WHERE sched_id='$id'", $ascent);
$rows = mysql_num_rows($result);

	echo" <div id='editschedule'>";
 
while ($row = mysql_fetch_assoc($result)){
	$id= $row['sched_id'];
	$name=$row['sched_activity'];
	$time=$row['sched_time'];
	$date=$row['sched_date'];
	$faculty=$row['sched_faculty'];
	$region=$row['sched_region'];
	//$result1 = mysql_query("SELECT * FROM region WHERE region_id='$region'", $ascent);
	//$row1 = mysql_fetch_assoc($result1);
	//$region_name=$row1['region_name'];
echo" 
<form action='' name='edit_stuff' method='POST' id='edit_stuff'>
<label for='file' style='color:white'>Schedule Date</label>
<input class='text' id='dates1' type='date' name='dates1' value='".$date."'  />

<label for='file' style='color:white'>Schedule Time</label>
<input class='text' id='times1' type='text' name='times1' value='".$time."'  />

<label for='file' style='color:white'>Activity</label>
<input class='text' id='activity1' type='text' name='activity1' value='".$name."'  />

<label for='file' style='color:white'>Faculty</label>
<input class='text' id='faculty1' type='text' name='faculty1' value='".$faculty."'  />
<input class='text' id='ids' type='hidden' name='ids' value='".$id."'  />
<ul class='actions'>                                        
                  <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id='edit' name='edit' onclick='editit()'  class='button button-style3 button-big' type='button'>Edit</button></li>
                 					  </ul>
                                      </form>	
";

}
echo "
	</div>";	
mysql_close($ascent);

 ?>