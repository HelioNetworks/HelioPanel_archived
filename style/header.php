<?php
session_start();
if(!session_is_registered(username)){
header("location:login/");
}

$username = $_SESSION['username'];
?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
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

<td height=102 style="background-image:url('images/toplogo.png'); background-repeat:no-repeat;" width="700">
	<p align="right"><font face="Verdana" color="#FFFFFF" size="5"><b>HelioPanel</b><br>
	
	<?php
	
	// Calculate home directory name
	$filename = '/home/'.$_SESSION['username'];

	if (file_exists($filename)) {
  		$homedir = '/home/'.$_SESSION['username'];
	} else {
	    $homedir = '/home1/'.$_SESSION['username'];
	}
	
	// Calculate size
	$sizebytes = filesize($homedir);
	$sizemb = $sizebytes / 1048576;
	$sizembr = round($sizemb);
	echo $sizembr;
	
	?>
	
	/250MB</font></td>
	
<td background="images/header-shadow-r.png" width=5></td>
<td background="images/headerbg.png"></td>
	
</tr>
<tr>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
<td height=5 bgcolor="#DAAE36"></td>
</tr>

<tr>
<td bgcolor="#F5F1E5"></td>
<td bgcolor="#DAAE36" background="images/body-shadow-l.png"></td>
<td bgcolor="#F5F1E5">