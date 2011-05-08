<?php

//////////////////////
// HelioPanel Login //
//////////////////////

// Relies on login form input
// Checks Stevie/Johnny for matching details and redirects
// Special thanks to byron for script

// Try Stevie

set_time_limit(60); 

if ( $_POST[username] ) 
{ 
$user_name = $_POST[username]; 
$user_pass = $_POST[password]; 

# random number for cookie file 
$rand = rand(1,1000000); 

$url = "http://stevie.heliohost.org:2082/frontend/x3/index.phpcp"; 
$agent = $_SERVER['HTTP_USER_AGENT']; 
$cook_file = "curl_login_cookie_$rand.txt"; 

# or per Geoff 
# $cook_file = tempnam(sys_get_temp_dir(), "curl_login_cookie_"); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HEADER, true); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true); 
curl_setopt($ch, CURLOPT_USERAGENT, $agent);  
curl_setopt($ch, CURLOPT_USERPWD, $user_name.":".$user_pass); 
curl_setopt($ch, CURLOPT_COOKIEFILE, "$cook_file"); 
curl_setopt($ch, CURLOPT_COOKIEJAR, "$cook_file"); 

# just discard the output. 
$src = curl_exec($ch); 

# grab http code 
$extract = curl_getinfo($ch); 
$httpcode = $extract['http_code']; 
curl_close($ch); 

if($httpcode == 200) 
{ 
session_register("username");
session_register("password"); 
header('location:../home.php');
} 

# delete cookie file 
if (file_exists($cook_file)) { 
unlink($cook_file); 
} 
} 

// Try Johnny

set_time_limit(60); 

if ( $_POST[username] ) 
{ 
$user_name = $_POST[username]; 
$user_pass = $_POST[password]; 

# random number for cookie file 
$rand = rand(1,1000000); 

$url = "http://johnny.heliohost.org:2082/frontend/x3/index.phpcp"; 
$agent = $_SERVER['HTTP_USER_AGENT']; 
$cook_file = "curl_login_cookie_$rand.txt"; 

# or per Geoff 
# $cook_file = tempnam(sys_get_temp_dir(), "curl_login_cookie_"); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_HEADER, true); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true); 
curl_setopt($ch, CURLOPT_USERAGENT, $agent);  
curl_setopt($ch, CURLOPT_USERPWD, $user_name.":".$user_pass); 
curl_setopt($ch, CURLOPT_COOKIEFILE, "$cook_file"); 
curl_setopt($ch, CURLOPT_COOKIEJAR, "$cook_file"); 

# just discard the output. 
$src = curl_exec($ch); 

# grab http code 
$extract = curl_getinfo($ch); 
$httpcode = $extract['http_code']; 
curl_close($ch); 

if($httpcode == 200) 
{ 
session_register("username");
session_register("password"); 
header('location:../home.php');
} 
else 
{
header('location:login.php?error=1');
}

# delete cookie file 
if (file_exists($cook_file)) { 
unlink($cook_file); 
} 
} 
?> 