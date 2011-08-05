<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['type'])) {
?>

<html>

<head>
<link href="../style.css" type="text/css" rel="stylesheet">
<title>Extract</title>
</head>

<body>

<table align="center" class="filespopupheader"><tr><td valign="middle"><center><b>Extract</b></center></td></tr></table>

<table align="center" class="filespopupcontent"><tr><td><center>

<form method=post action=extract.php>
<input type=hidden name=file value="<?php echo $_GET['file']; ?>">
<input type=hidden name=path value="<?php echo $_GET['path']; ?>">

<p><?php echo $_GET['file']; ?></p>

<p>
<select name=type>
<option value=zip>ZIP Archive</option>
</select>
</p>

<input type=submit value="Extract">

</form>

</center></td></tr></table>

</body>

</html>
	
<?php
}else{
	
	if ($_POST['type'] == 'zip') {
		
		$zip = new ZipArchive;
		if ($zip->open($_POST['path']."".$_POST['file']) === TRUE) {
		    $zip->extractTo($_POST['path']);
		    $zip->close();
		} else {
	    	die('The compressed archive could not be extracted. Please try again.');
		}
	
	}elseif ($_POST['type'] == 'gz') {
		
		$file = $_POST['path']."".$_POST['file'];
		$extract = pathinfo($file);
		$fn = ($extract['filename']);
		$data = file_get_contents("compress.zlib://$file");
		file_put_contents($fn, $data);

	}elseif ($_POST['type'] == 'tar') {
		
		require 'tarfunction.php';
		untar($_POST['path']."".$_POST['file']);

	}
	?>
	
	<html>
	
	<head>
	<script type="text/javascript">
	window.opener.document.location.reload(true);
	</script>
	</head>
	
	<body>
	<br><br><center><font face=arial><b>The file has been extracted.</b></font><br><font face=arial>Please close this window.</font></center>
	</body>
	
	</html>
	
	<?php
}