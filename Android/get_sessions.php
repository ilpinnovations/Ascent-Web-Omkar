<?php 
$con=mysql_connect("localhost","jeet","J@447788");

$sql="USE ascent_db";
mysql_query($sql,$con);

$date=explode("-",$_GET["date"]);
$emp_id = $_GET['emp_id'];

$regionId=$_GET["regionId"];


$sql = "SELECT * FROM ascent_db.schedule WHERE sched_date='".$_GET["date"]."' AND sched_region='$regionId'";
$result=mysql_query($sql,$con);
$rows1 = mysql_num_rows($result);
if($rows1==0)
{
	$data1=array(
"message"=>'No schedule available'
);
echo json_encode(array('data' =>$data1));
}
else
{
	while($conn=mysql_fetch_assoc($result))
{
$schedule_id = $conn["sched_id"];
$sql1 = "SELECT * FROM ascent_db.feed WHERE scheduleid='$schedule_id' AND employeeid='$emp_id'";
$result1=mysql_query($sql1,$con);
$rows = mysql_num_rows($result1);
if($rows>=1)
{
	$data1=array(
"is_feedback"=>true
);
}
else
{
	$data1=array(
"is_feedback"=>false
);
}

$data[]= array ('data1'=>$conn,'data2'=>$data1);
}
echo json_encode(array('data' =>$data));
}
mysql_close($con);

?>