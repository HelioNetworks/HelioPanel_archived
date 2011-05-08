<?php 
// Turn error reporting off
error_reporting(0);
?>

<html>

<head>
<title>HelioPanel Login</title>
</head>

<body bgcolor=#eee>

<table width=300 height=400 align=center background="../images/loginbg.png" style="font-family:Verdana;"><tr><td>

<center><b><u><font size=2>Login</font></u></b></center><br>

<?php 

if ($_GET['error'] == '1') {
	echo "<center><b><font color=red size=2>Failed to login</font></b></center><br>";
}

?>

<form method=post action=dologin.php>

<table border=0>

<tr>
<td width=110 style="text-align:right;"><font size=2>Username:</font></td>
<td width=190><input type="text" name="username"></td>
</tr>

<tr>
<td width=110 style="text-align:right;"><font size=2>Password:</font></td>
<td width=190><input type="password" name="password"></td>
</tr>

<tr>
<td width=110 style="text-align:right;"><font size=2>Server:</font></td>
<td width=190><select name=server><option value="stevie">Stevie</option><option value="johnny">Johnny</option></select></td>
</tr>

</table>

<center><input type=submit value="Login"></center>

</form>

</td></tr></table>

<center><font size=1 face=arial color=#bbb><br>&copy; Copyright Helio Networks 2011.<br>All trademarks and copyrights are property of their respective owners</font></center>

</body>

</html>