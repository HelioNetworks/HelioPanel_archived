<html>

<head>
<script type="text/javascript">
<!--
function confirmation() {
	var answer = confirm("Are you sure you wish to permanently delete <?php echo $_GET['file']; ?> ?")
	if (answer){
		window.location = "../delete.php?path=<?php echo $_GET['path']; ?>&file=<?php echo $_GET['file']; ?>";
	}
	else{
		window.location = "../../files.php?path=<?php echo $_GET['path']; ?>";
	}
}
//-->
</script>
</head>

<body onload="confirmation()">
<a href="files/delete.php?file=<?php echo $_GET['path'].''.$_GET['file']; ?>">Confirm Delete</a> for non-javascript browsers
</body>