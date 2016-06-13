<?php
//error_reporting(0);
$hostname_ascent = "localhost";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent) or trigger_error(mysql_error(),E_USER_ERROR); 
?>