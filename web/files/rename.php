<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['newfilename'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>Rename</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>Rename</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form method=post action=rename.php>
<input type=hidden name=path value="<?php echo $_GET['path']; ?>">
<input type=hidden name=oldfilename value="<?php echo $_GET['file']; ?>">

<p><?php echo $_GET['path']."".$_GET['file']; ?></p>
<b>will be renamed to:</b><br>
<input type=text name=newfilename value="<?php echo $_GET['file']; ?>" style="width:90%; text-align:center;">

<br><br>

<input type=submit value="Rename">

</form>

</center></td></tr></table>

</body>

</html>

<?php
}else{

	$fileRepository->rename($_POST['path']."".$_POST['oldfilename'], $_POST['path']."".$_POST['newfilename']);
	?>

	<html>

	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>

	<body>
	<br><br><center><font face=arial><b>The file has been renamed.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>

	</html>

	<?php
}