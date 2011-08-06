<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

// Include the configuration
require '../config.php';

if (!isset($_POST['path'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>Upload File</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>Upload File</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form enctype="multipart/form-data" method=post action=uploadfile.php>
<input type=hidden name=path value=<?php echo $_GET['path']; ?>>
<input type="hidden" name="MAX_FILE_SIZE" value="262144000" />
<input type=file name=uploadedfile>

<br><br>

<input type=submit value="Upload File">

</form>

</center></td></tr></table>

</body>

</html>
	
<?php
}else{
	
$target_path = $_POST['path'] . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) { ?>

	<html>
	
	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>
	
	<body>
	<br><br><center><font face=arial><b>The file has been uploaded.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>
	
	</html>
	
<?php
}else{
    echo "There was an error uploading the file; please try again.";
}

}