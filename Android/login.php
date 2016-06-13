<?php
$con=mysql_connect("localhost","jeet","J@447788");

$sql="USE ascent_db";
mysql_query($sql,$con);
?>
<?php
error_reporting(0);
$emp_id = $_GET['emp_id'];
$emp_email=$_GET['emp_email'];
$emp_name=$_GET['emp_name'];
$region=$_GET['region'];
if(isset($emp_id))
{
	
$query = "SELECT * FROM `employee` WHERE `emp_id`='$emp_id' AND `emp_email`='$emp_email'";
$result = mysql_query($query,$con);
$rows = mysql_num_rows($result);
if($rows==1)
{
$query1 = "SELECT * FROM `employee` WHERE `emp_id`='$emp_id' AND emp_region='$region'";
$result1 = mysql_query($query1,$con);
$rows1 = mysql_num_rows($result1);
if($rows1==1)
{
}
else
{
$query2 = "UPDATE `employee` SET emp_region='$region' WHERE `emp_id`='$emp_id' AND `emp_email`='$emp_email'";
$result2 = mysql_query($query2,$con);	
}
echo "You have already registered";
}
else{
$query = "INSERT INTO ascent_db.employee values ($emp_id, '$emp_name', $region, '$emp_email')";
$result = mysql_query($query,$con);
echo "You are successfully registered.";
}
}
mysql_close($talent_uk);
?>