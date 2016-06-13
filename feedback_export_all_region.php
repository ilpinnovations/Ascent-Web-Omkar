<?php
$DB_Server = "localhost"; 
$DB_Username = "jeet"; 
$DB_Password = "J@447788"; 
$DB_DBName = "ascent_db"; 
$DB_TBLName = "feedbacks"; 
$xls_filename = 'feedback_form.xls';
$schedule_id= $_REQUEST['schedule_id'];
$sched_date= $_REQUEST['sched_date'];
$sched_region= $_REQUEST['region'];
$sql = "SELECT 
region_name AS 'Region',
emp_id AS 'Employee ID',
emp_name AS 'Employee Name',
emp_email AS 'Employee Email',
sched_activity AS 'Session',
sched_date AS 'Date',
sched_faculty AS 'Faculty',
knowledge AS 'Rate the knowledge of the faculty',
articulation AS 'Rate the faculty inn terms of articulation and delivery',
time_management AS 'Rate the facultys ability too manage time',
interaction AS 'Rate the level of interaction of faculty withh you',
quality_of_course AS 'Rate the quality of course material(iff given)',
course_objective AS 'were the objectives of the course met',
time AS 'Was the duration of the time allocated too the course adequate',
toadd AS 'List two concepts that must be added too the course',
toremove AS 'List concepts that should be dropped fromm the course',
willimplement AS 'What learning fromm this course do you plan too implement?'
FROM feedbacks Where sched_date='$sched_date'";
$Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password) or die("Failed to connect to MySQL:<br />" . mysql_error() . "<br />" . mysql_errno());

$Db = @mysql_select_db($DB_DBName, $Connect) or die("Failed to select database:<br />" . mysql_error(). "<br />" . mysql_errno());

$result = @mysql_query($sql,$Connect) or die("Failed to execute query:<br />" . mysql_error(). "<br />" . mysql_errno());
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
$sep = "\t"; 
for ($i = 0; $i<mysql_num_fields($result); $i++) {
  echo mysql_field_name($result, $i) . "\t";
}
print("\n");
while($row = mysql_fetch_row($result))
{
  $schema_insert = "";
  for($j=0; $j<mysql_num_fields($result); $j++)
  {
    if(!isset($row[$j])) {
      $schema_insert .= "NULL".$sep;
    }
    elseif ($row[$j] != "") {
      $schema_insert .= "$row[$j]".$sep;
    }
    else {
      $schema_insert .= "".$sep;
    }
  }
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert));
  print "\n";
}
?>