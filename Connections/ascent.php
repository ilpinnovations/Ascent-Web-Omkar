<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_ascent = "160.153.89.96";
$database_ascent = "ascent_db";
$username_ascent = "jeet";
$password_ascent = "J@447788";
$ascent = mysql_pconnect($hostname_ascent, $username_ascent, $password_ascent) or trigger_error(mysql_error(),E_USER_ERROR); 
?>