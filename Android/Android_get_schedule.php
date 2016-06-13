<?php 
$con=mysql_connect("localhost","jeet","J@447788");

$sql="USE ascent_db";
mysql_query($sql,$con);

$date=explode("-",$_GET["date"]);

$regionId=$_GET["regionId"];


$sql = "SELECT * FROM ascent_db.schedule WHERE sched_date='".$_GET["date"]."' AND sched_region=$regionId";
$result=mysql_query($sql,$con);




echo '[';


$i=0;
while($conn=mysql_fetch_array($result))
{
if($i==0)
echo '{
        "title": "'.$conn["sched_activity"].'",
        "image": "http://cchat.in/chennai/",
        "rating": "'.$conn["sched_faculty"].'",
        "releaseYear": "'.$conn["sched_time"].'",
        "genre": ["'.$conn["sched_date"].'","'.$conn["sched_region"].'","'.$conn["image"].'","'.$conn["sched_id"].'"]
    }';
else
echo ',{
         "title": "'.$conn["sched_activity"].'",
        "image": "http://cchat.in/chennai/",
        "rating": "'.$conn["sched_faculty"].'",
        "releaseYear": "'.$conn["sched_time"].'",
        "genre": ["'.$conn["sched_date"].'","'.$conn["sched_region"].'","'.$conn["image"].'","'.$conn["sched_id"].'"]
    }';

$i++;
}

echo ']';

?>