<?php
$id = $_REQUEST['id'];
$date= $_REQUEST['date'];
$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$query_member = sprintf("DELETE FROM schedule WHERE sched_id='$id'");
$Recordset1 = mysql_query($query_member, $ascent) or die(mysql_error());
$result = mysql_query("SELECT * FROM schedule WHERE sched_date='$date'", $ascent);
$rows = mysql_num_rows($result);
if($rows==0)
{
echo "<b>No activities found for the selected date or region</b>";
}
else
{
	echo"<table border='1' class='table table-striped table-advance table-hover'>
 <thead>
 <tr>

<th class='hidden-phone'><i class='fa fa-calendar'></i> Date</th>
<th><i class='fa fa-clock-o'></i> Time</th>
<th><i class='fa fa-bullhorn'></i> Activity</th>
<th><i class='fa fa-bullhorn'></i> Faculty</th>
<!---<th><i class=' fa fa-edit'></i> Edit</th>-->
<th><i class=' fa fa-trash-o'></i> Delete</th>
 </tr>
</thead>";

$result6 = mysql_query("SELECT * FROM schedule WHERE sched_date='$date'", $ascent);

while($row5 = mysql_fetch_array($result6)) {
	$id= $row5['sched_id'];
	$name=$row5['sched_activity'];
	$time=$row5['sched_time'];
	$date=$row5['sched_date'];
	$faculty=$row5['sched_faculty'];
$result7 = mysql_query("SELECT * FROM feedbacks WHERE sched_id='$id'", $ascent);
$rows7 = mysql_num_rows($result7);	
							  echo "<tbody>
                              <tr>
                                  <td>" . $date . "</td>
                                  <td>" . $time . "</td>
								  <td>" . $name. "</td>
                                  <td>" . $faculty . "</td>
<!--<td> <a class='btn btn-primary btn-xs' href=''><i class=' fa fa-pencil'></i></a></td>--->";
 
if($rows7>0)
{
echo "<td> <a class='btn btn-success btn-xs' onClick='message()'><i class='fa fa-times'></i></a></td>";
}
else
{

echo "<td><button type='button' class='btn btn-danger btn-xs' onClick='delete_activity(".$id.")'><i class=' fa fa-trash-o'></i> </button></td>";
}
echo " </tr>";							 
}
echo" </tbody>";
echo "</table>";
echo" <div id='schedule'>
 <label for='idSession' style='color:white'>Select Activity to Edit</label>";
echo "<select class='text' id='sessions' name='sessions' onchange='get_editdetails()'>"; 
echo "<option value=''></option>";
while ($row = mysql_fetch_assoc($result)){
	$id= $row['sched_id'];
	$name=$row['sched_activity'];
	$time=$row['sched_time'];
	
echo "<option value=$id>$name ($time)</option>";	

}
echo "</select>
	</div>
	<div id='editschedule'>
         
         
         </div>";
}
mysql_close($ascent);
?>