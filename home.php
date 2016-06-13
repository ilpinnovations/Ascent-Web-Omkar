<?php
$schedule_id=$_POST['ids'];

error_reporting(E_ALL);
include 'ascent.php';


function getResponseString() {
    if (!isset($_POST['submit'])) {
        return '';
    }
    if (!isset($_POST['regionId']) || $_POST['regionId'] == 0) {
        return 'You must select a valid region';
    }
    if (!isset($_FILES['file'])) {
        return 'You must select a .csv file';
    }
    $region = $_POST['regionId'];
    $file = $_FILES['file'];
    $originalName = $file['name'];
    $serverFilePath = 'tmp_files/'.$originalName;

    if (file_exists($serverFilePath)) {
        return 'Not updated, A file with this filename has already been uploaded. Please rename the file.';
    }

    move_uploaded_file($file['tmp_name'], $serverFilePath);
    $contents = file_get_contents($serverFilePath);
    if (!$contents)
        return 'Error uploading the file';

    $ascent = new ASCENT();
    $status = $ascent->insertIntoScheduleByCSVContents($contents, $region);

    if ($status)
        return 'Successfully updated the schedule';

    return 'Error updating the schedule';
}

function getRegionList() {
    $string = "<option value='0'>Select Region</option>";
    $ascent = new ASCENT();

    $result = $ascent->getRegionListArray();
    for ($i = 0; $i < sizeof($result); $i++) {
        $string .= "<option value='".$result[$i]['region_id']."'>".$result[$i]['region_name']."</option>";
    }

    return $string;
}

?>
<?php


function functionModeExecution() {

    if (isset($_GET['date']) && isset($_GET['region']) && !isset($_GET['session'])) {

        // date and region are given, but not session, throw json with list of sessions;
        $date = $_GET['date'];
        $region = $_GET['region'];

        $ascent = new ASCENT();
        $sessionList = $ascent->getSessionList($date, $region);
        echo $sessionList;
        exit;
    }

}

functionModeExecution();


function getRegionList1() {
    $string = "";
    $ascent = new ASCENT();

    $result = $ascent->getRegionListArray();
    for ($i = 0; $i < sizeof($result); $i++) {
        $string .= "<option value='".$result[$i]['region_id']."'>".$result[$i]['region_id'].". ".$result[$i]['region_name']."</option>";
    }

    return $string;
}


?>
<?php
 if(isset($_POST['edit'])){
	 
	 $id= $_POST['ids'];
	$name=$_POST['activity'];
	$time=$_POST['times'];
	$date=$_POST['dates'];
	$faculty=$_POST['faculty'];
	 $hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("UPDATE schedule SET sched_date='$date',sched_time='$time',sched_activity='$name',sched_faculty='$faculty'", $ascent);
if($result){
echo "<script type=\"text/javascript\">".
        "alert('Update Successful');".
        "</script>";
 }
	 }
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Ascent</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400italic,700,900" rel="stylesheet" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
         <link rel="stylesheet" type="text/css" href="pace.css">    
     <script src="pace.js"></script>
        <script>
		 function message()
   { 
alert('This session cannot be deleted, There are feedbacks submitted for this session.');
 }

		function get_activities()
{
	var date=document.getElementById('idDate').value;
	var region=document.getElementById('regionIds').value;
	if(region=="allregion")
	{
		document.getElementById('schedule').style.display= 'none';
		}
	else
	{
		document.getElementById('schedule').style.display= 'block';
	//var region=document.getElementById('idRegion').value;
	
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
  document.getElementById("schedule").innerHTML = xmlhttp.responseText;
	}
  }
xmlhttp.open("GET","get_schedule.php?date="+date+"&region="+region,true);
xmlhttp.send();
	}
}  

function get_list()
   { 
   var date=document.getElementById('idDate').value;
var region=document.getElementById('regionIds').value;
	//var region=document.getElementById('idRegion').value;

	var activity=document.getElementById('idSession').value;
	
		if(region=="allregion")
		{
			if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
  document.getElementById("schedule").innerHTML = xmlhttp.responseText;
  alert(xmlhttp.responseText);
  if(xmlhttp.responseText=="done"){
	  window.location ="http://theinspirer.in/ascent/feedback_export_all_region.php?schedule_id="+activity+"&sched_date="+date+"&region="+region+"";
	  }
  else
  {
	 alert('No feedbacks available');
	  }
	}
  }
xmlhttp.open("GET","validate_feedback.php?date="+date,true);
xmlhttp.send();
			
			}
			else if(activity=="all"){
		window.location ="http://theinspirer.in/ascent/feedback_export_all.php?schedule_id="+activity+"&sched_date="+date+"&region="+region+"";
		}
	else
	{
window.location ="http://theinspirer.in/ascent/feedback_export.php?schedule_id="+activity+"&sched_date="+date+"&region="+region+"";
}



 }
 
 function get_editschedule()
{
	var date=document.getElementById('dates').value;
	//var region=document.getElementById('regions').value;
	
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
  document.getElementById("edit_shed").innerHTML = xmlhttp.responseText;
	}
  }
xmlhttp.open("GET","edit_schedule.php?date="+date,true);
xmlhttp.send();
}  

 function get_editdetails()
{
	var schedule_id=document.getElementById('sessions').value;
	//var region=document.getElementById('regions').value;
	
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
 
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
 
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
	  
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
		
  document.getElementById("editschedule").innerHTML = xmlhttp.responseText;
	}
  }
xmlhttp.open("GET","get_scheduledetails.php?id="+schedule_id,true);
xmlhttp.send();
}  
function editit()
{
	var dates1=document.getElementById('dates1').value;
	
	var times1=document.getElementById('times1').value;
	
	var activity1=document.getElementById('activity1').value;
	
	var id1=document.getElementById('ids').value;
	
	var faculty1=document.getElementById('faculty1').value;
	
	//var region=document.getElementById('regions').value;
	
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
 
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
 
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
	  
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
		 alert('Update successful');
		 var msg = new SpeechSynthesisUtterance('Update Successful');
window.speechSynthesis.speak(msg);
  document.getElementById("edit_shed").innerHTML = xmlhttp.responseText;
	}
  }
xmlhttp.open("GET","update.php?id="+id1+"&date="+dates1+"&time="+times1+"&activity="+activity1+"&faculty="+faculty1,true);
xmlhttp.send();
}

function delete_activity(str)
   { 
   var msg = new SpeechSynthesisUtterance('Do you really want to delete this activity?');
window.speechSynthesis.speak(msg);
   var result=confirm("Do you really want to delete this activity?");
if(result)
{
  var date=document.getElementById('dates').value;
 if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
		 alert('Activity deleted successfully')
 var msg = new SpeechSynthesisUtterance('Activity deleted successfully');
window.speechSynthesis.speak(msg);
  document.getElementById("edit_shed").innerHTML = xmlhttp.responseText;

	}
  }
xmlhttp.open("GET","delete_session.php?id="+str+"&date="+date,true);
xmlhttp.send();
}
 }
</script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
		</noscript>
	</head>
	<body class="homepage">

		<!-- Header Wrapper -->
			<div id="header-wrapper" class="wrapper">
				<div class="container">
					<div class="row">
						<div class="12u">
						
							<!-- Header -->
								<div id="header">
									
									<!-- Logo -->
										<div id="logo">
											<h1><a href="#">Ascent</a></h1>
											<!--<span class="byline">A free responsive site template by HTML5 UP</span>-->
										</div>
									<!-- /Logo -->
									
									<!-- Nav -->
										<nav id="nav">
											<ul>
												<li class="current_page_item"><a href="home.php">Home</a></li>
												<!---<li>
													<span>Dropdown</span>
													<ul>
														<li><a href="#">Lorem ipsum</a></li>
														<li><a href="#">Magna veroeros</a></li>
														<li><a href="#">Etiam nisl</a></li>
														<li>
															<span>Sed consequat</span>
															<ul>
																<li><a href="#">Lorem dolor</a></li>
																<li><a href="#">Amet consequat</a></li>
																<li><a href="#">Magna phasellus</a></li>
																<li><a href="#">Etiam nisl</a></li>
																<li><a href="#">Sed feugiat</a></li>
															</ul>
														</li>
														<li><a href="#">Nisl tempus</a></li>
													</ul>
												</li>-->
												<li><a href="#upload">Upload Schedule</a></li>
												<li><a href="#download">Download Feedback</a></li>
                                                <li><a href="#edit">Edit Schedule</a></li>
												<!---<li><a href="#edit">Edit Schedule </a></li>-->
											</ul>
										</nav>
									<!-- /Nav -->

								</div>
							<!-- /Header -->

						</div>
					</div>
				</div>
			</div>
		<!-- /Header Wrapper -->
		<p>&nbsp;</p>
                                  <p>&nbsp;</p>
                                  <p>&nbsp;</p>
                                  
		<!-- Intro Wrapper -->
			<div id="intro-wrapper" class="wrapper wrapper-style1">
				<div id="upload" class="title">Upload Schedule</div>
				<div class="container">
					<div class="row">
						<div class="12u" align="center">
							
							<!-- Intro -->
								<section id="intro">
									
									<p class="style2">
										Upload New Schedule
									</p>
                                    
                                   

												<!-- Contact Form -->
													<section class="footer-one">
								<form method="post" action="#" enctype="multipart/form-data">
															<div>
								<div class="row half">
								<div class="6u">
                                 <label for="file" style="color:white">Select .CSV File</label>
								<input class="text" type="file" name="file" accept=".csv" required/>
								</div>
								<div class="6u">
                                 <label for="regionId" style="color:white">Select Region</label>
								<select class="text" name="regionId" required><?=getRegionList(); ?></select>
								</div>
								</div>
								
								</div>
								<ul class="actions">                                        
                  <li><button class="button button-style3 button-big" type="submit" name="submit">Upload Schedule</button></li>
                  <li><a href="http://theinspirer.in/ascent/CSV_Format/sample.csv" class="button button-style3 button-big" type="submit" name="format">Download Format</a></li>
								  </ul>
                                    <p>&nbsp;</p>
                                  <p>&nbsp;</p>
                                    <div class="response" id="resp"><?=getResponseString();?></div>
                                      </form>
													</section>
												<!-- /Contact Form -->

											
                                            
									
									
								</section>
							<!-- /Intro -->
								
						</div>
					</div>
				</div>
			</div>
		<!-- /Intro Wrapper -->
		
		<!-- Main Wrapper -->
			<div class="wrapper wrapper-style2">
				<div id="download" class="title">Download Feedback</div>
				<div class="container">
					<div class="row">
						<div class="12u">
							
							<!-- Main -->
								<div id="main">
								
									<!-- Features -->
										<section id="features">
											<header class="style1">
												<h2>Download Feedback</h2>
												
											</header>
											<section class="footer-one">
								 <form method="get" action="download.php" enctype="application/x-www-form-urlencoded">
															<div>
								<div class="row half" align="center">
								<div class="12u">
                                 <label for="file" >Select Date</label>
							<input class="text" id="idDate" type="date" name="idDate" onChange='get_activities();' value="<?php echo date('Y-m-d'); ?>"  />
								 <?php  
$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent);
mysql_select_db($database_ascent, $ascent);
$result = mysql_query("SELECT * FROM region", $ascent);
 echo "<label for='regionIds' >Select Region</label>";
echo "<select name='regionIds' id='regionIds' class='text' onChange='get_activities();' required>"; 
echo "<option value=''></option>";
echo "<option value='allregion'>all</option>";
while ($row = mysql_fetch_assoc($result)){
	$id= $row['region_id'];
	$name=$row['region_name'];
	
	echo "<option value=$id>$name</option>";
	}
	echo "</select>";
  ?>
  <div id="schedule" class="12u">
  <?php
echo "<select name='idSession' id='idSession' class='text' style='visibility:hidden;'>"; 
echo "<option value=''></option>";
echo "<option value='allregion'>all</option>";
	echo "</select>";
	?>
								</div>
	
		<ul class='actions'>                                        
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button  class='button button-style3 button-big' type='button' onClick='get_list()' name='submit'>Download</button></li>
								  </ul>
		
                               
							</div>
                                
                                
								</div>
								
								</div>
								
                                    
                                    <div class="response" id="resp"></div>
                                    </form>
										  </section>
										</section>
									<!-- /Features -->
									
								</div>
							<!-- /Main -->
							
						</div>
					</div>
				</div>
			</div>
		<!-- /Main Wrapper -->
		<div class="wrapper wrapper-style1">
				<div id="edit" class="title">Edit Schedule</div>
				<div class="container">
					<div class="row">
						<div class="12u">
							
							<!-- Main -->
								<div id="main">
								
									<!-- Features -->
										<section id="features">
											<header class="style1">
												<h2>Edit Schedule</h2>
												
											</header>
											<section class="footer-one">
								 <form method="get" action="download.php" enctype="application/x-www-form-urlencoded">
															<div>
								<div class="row half" align="center">
								<div class="12u">
                                 <label for="file" style="color:white">Select Date</label>
							<input class="text" id="dates" type="date" name="dates" onChange="get_editschedule()" value="<?php echo date('Y-m-d'); ?>"  />
								</div>
                                </form>
                                </br>
                                <div class="12u">
                                <div id="edit_shed">
                                
                                
         </div>
         </div>
								
                                <div class="12u">
                                <div id="schedule">
                                
         </div>
         </div>
         <div class='12u'>
         
         </div>
         <div class='12u'>
         
         </div>
         
                                
				  </div>
								
		  </div>
							
                                    
                                    <div class="response" id="resp"></div>
                                    
													</section>
										</section>
									<!-- /Features -->
									
	</div>
							<!-- /Main -->
							
		  </div>
	</div>
				</div>
			</div>
		

		<!-- Footer Wrapper -->
			<div id="footer-wrapper" class="wrapper">
				<div class="title">&copy; ILP Innovations</div>
				
			</div>
		<!-- /Footer Wrapper -->

</body>
</html>