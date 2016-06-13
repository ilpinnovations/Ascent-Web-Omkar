<?php
 include('dbtemp.php'); 
	$scheduleid=$_POST['scheduleId'];
	$employeeid=$_POST['employeeId'];
	$knowledge=$_POST['knowledge'];
	$articulation=$_POST['articulation'];
	$time_management=$_POST['time_management'];
	$interaction=$_POST['interaction'];
	$quantity_of_course=$_POST['quality_of_course'];
	$course_objectives=$_POST['course_objectives'];
	$time=$_POST['time'];
	$toAdd=$_POST['toAdd'];
	$toRemove=$_POST['toRemove'];
	$willImplement=$_POST['willImplement'];
	
 
	if($query=mysqli_query($con,"INSERT INTO `feed` (`feedid`, `scheduleid`, `employeeid`, `knowledge`, `articulation`, `time_management`, `interaction`, `quality_of_course`, `course_objective`, `time`, `toadd`, `toremove`, `willimplement`) VALUES (NULL, '$scheduleid', '$employeeid', '$knowledge', '$articulation', '$time_management', '$interaction', '$quantity_of_course', '$course_objectives', '$time', '$toAdd', '$toRemove', '$willImplement')"))
	{    $ar=array('response_type' => 'FEEDBACK','success' => true,'response' => 'Successfully registered the feedback');
		echo json_encode($ar);
		
		}else{
			$ar=array('response_type' => 'FEEDBACK','success' => false,'response' => 'Failure');
		echo json_encode($ar);
			
			
		}
		






?>