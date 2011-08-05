<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['filename'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>New File</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>New File</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form method=post action=newfile.php>
<input type=hidden name=path value=<?php echo $_GET['path']; ?>>
<b>File Name:</b><br>
<input type=text name=filename style="width:90%; text-align:center;">

<br><br>

<input type=submit value="Create File">

</form>

</center></td></tr></table>

</body>

</html>
	
<?php
}else{
	
	$filehandle = fopen($_POST["path"].''.$_POST["filename"], 'w') or die("<b>HelioPanel is unable to create the file. Please check file permissions.</b>");
	?>
	
	<html>
	
	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>
	
	<body>
	<br><br><center><font face=arial><b>The file has been created.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>
	
	</html>
	
	<?php
}