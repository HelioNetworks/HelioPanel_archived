<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['delete'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>Delete</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>Delete</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form method=post action=delete.php>
<input type=hidden name=file value="<?php echo $_GET['file']; ?>">

<p><?php echo $_GET['file']; ?></p>
<b>Are you sure you want to delete this file? This cannot be undone!</b><br>

<input type=submit value="Delete!" name="delete">

</form>

</center></td></tr></table>

</body>

</html>
	
<?php
}else{
	
	unlink($_POST['file']);
	?>
	
	<html>
	
	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>
	
	<body>
	<br><br><center><font face=arial><b>The file has been permanently deleted.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>
	
	</html>
	
	<?php
}