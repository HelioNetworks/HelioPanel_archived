<?php 

if ($_GET['r'] == 'logout') {
	session_start();
	session_destroy();
}

if (!isset($_POST['username'])) {
?>

<html>

<head>
<title>HelioPanel Login</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>

<body class=loginbody>

<table class=login align=center><tr><td valign=middle>
<p><b><u><font size=3>Login to HelioPanel</font></u></b></p>

<?php
if (isset($_GET['error'])) {
	echo "<p><font color=red size=2><b>Login Attempt Failed!</b></font></p>";
}

if ($_GET['r'] == 'logout') {
	echo "<p><font color=red size=2><b>You have been logged out.<br>Thank you for using HelioPanel.</b></font></p>";
}
?>


<form method=post action=login.php>
Username: <input type=text name=username><br>
Password: <input type=password name=password>
<p><input type=submit value=Login style="font-weight:bold;"></p>
</form>

</td></tr></table>

<div class=logincopyrights><p>
&copy; Copyright Helio Networks 2011.<br>
All trademarks and copyrights are property of their respective owners
</p></div>

</body>

</html>

<?php 
}else{

	require 'config.php';
	
	if ($_POST['username'] == $username && $_POST['password'] == $password) {
		session_start();
		$_SESSION['username'] = $username;
		header("location:./");
	}else{
		header("location:login.php?error=1");
	}
	
}?>