<?php 
$con=mysql_connect("localhost","jeet","J@447788");

$sql="USE ascent_db";
mysql_query($sql,$con);

$sql = "SELECT * FROM ascent_db.region";
$result=mysql_query($sql,$con);
$rows1 = mysql_num_rows($result);
if($rows1==0)
{
	$data1=array(
"message"=>'No regions available'
);
echo json_encode(array('data' =>$data1));
}
else
{
	while($conn=mysql_fetch_assoc($result))
{

$data[]=$conn;
}
echo json_encode(array('data' =>$data));
}
mysql_close($con);

?>