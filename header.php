<?php

session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:login.php");
}

// Include the configuration
require 'config.php';
require 'FileRepository.php';

$fileRepository = new FileRepository("http://".$_SERVER['HTTP_HOST']."/heliopanel/hook.php", $authKey);

// Get the user's home directory
if (file_exists('/home/'.$username)) {
    $homedir = '/home/'.$username;
} elseif (file_exists('/home1/'.$username)) {
    $homedir = '/home1/'.$username;
} else {
	die ('Fatal Error: Cannot find home directory!');
}
?>

<html>

<head>
<link href="style.css" type="text/css" rel="stylesheet">
<title>HelioPanel</title>

<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=200,left = 0,top = 0');");
}
// End -->
</script>

</head>

<body bgcolor="#E9C55C" style="margin-top:0px; margin-left:0px; margin-right:0px;">

<div align="center">

<table width="100%" cellspacing=0 cellpadding=5 style="border-radius:10px;" bgcolor="#FFFFFF">
<tr>

<td background="images/headerbg.png"></td>
<td background="images/header-shadow-l.png" width=5></td>

<td onclick="window.location='./'" width=714 height=102 style="background-image:url('images/toplogo.png'); background-repeat:no-repeat; cursor:pointer;">
	<p align="right"><font face="Verdana" color="#FFFFFF" size="5"><b>HelioPanel</b><br>
	</font></td>

<td background="images/header-shadow-r.png" width=5></td>
<td background="images/headerbg.png"></td>

</tr>
<tr>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
<td width=714 height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
</tr>

<tr>
<td bgcolor="#F5F1E5"></td>
<td bgcolor="#DAAE36" background="images/body-shadow-l.png"></td>
<td bgcolor="#F5F1E5" width=714>
