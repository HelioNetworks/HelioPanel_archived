<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['newpath'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>Move</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>Move</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form method=post action=move.php>
<input type=hidden name=oldpath value="<?php echo $_GET['path']; ?>">
<input type=hidden name=filename value="<?php echo $_GET['file']; ?>">

<p><?php echo $_GET['path']."".$_GET['file']; ?></p>
<b>will be moved to:</b><br>
<input type=text name=newpath value="<?php echo $_GET['path']; ?>" style="width:90%; text-align:center;">

<br><br>

<input type=submit value="Move">

</form>

</center></td></tr></table>

</body>

</html>

<?php
}else{

	$fileRepository->rename($_POST['oldpath']."".$_POST['filename'], $_POST['newpath']."".$_POST['filename']);
	?>

	<html>

	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>

	<body>
	<br><br><center><font face=arial><b>The file has been moved.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>

	</html>

	<?php
}