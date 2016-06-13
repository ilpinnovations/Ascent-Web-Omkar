<?php
 include('dbtemp.php'); 
 $type=$_POST['type'];	
if($type=='C'){
$date=$_POST['date'];
 $associate_id=$_POST['as-id'];
 $associate_name=$_POST['as-name'];
 $domainrate=$_POST['domain-rate'];
 $presentation=$_POST['presentation-rate'];
 $timeman=$_POST['time-rate'];
 $inter=$_POST['inter-rate'];
 $obj=$_POST['objective-rate'];
 $course=$_POST['course-rate'];
 $otherfeed=$_POST['other-feed'];
 
	if($query=mysqli_query($con,"INSERT INTO `sess_feedback` (`date`, `associate_id`, `associate_name`, `domain_knowledge`, `presentation`, `time_management`, `inter_activeness`, `objectives`, `course_material`, `any_other_feedback`) VALUES ('$date', $associate_id, '$associate_name', $domainrate, $presentation, $timeman, $inter, $obj,$course, '$otherfeed')"))
	{    
		echo "Insertion successfull";}else{
			echo "Insertion Unsuccessfull";
		}
		
}
if($type=='D'){
	 $f_id=$_POST['f_id'];
		if($delquery=mysqli_query($con,"DELETE FROM `sess_feedback` WHERE `f_id` = $f_id"))
	{    
		echo "DELETE successfull";}
		else{
			echo "Unsuccessfull";
		}
	
}





?>