<?php 
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

// If the form hasn't been submitted
if (!isset($_POST['content'])) {

?>

<html>
<head>
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
 	<link href="../style.css" type="text/css" rel="stylesheet">
	<title>WYSIWYG Editor</title>
</head>
<body style="background:transparent;">

<table class="filestoolbar"><tr>
<td width=40 valign=middle><img border=0 src="../images/files/back-icon.png" style="cursor:pointer; opacity:0.5;filter:alpha(opacity=50)" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100" onmouseout="this.style.opacity=0.5;this.filters.alpha.opacity=50" onClick="top.window.location='../files.php?path=<?php echo $homedir; ?>/'"></td>
<td valign=middle><input type=text name=file value="<?php echo $_GET['file']; ?>" style="width:98%;" disabled></td>
<td width=200 valign=middle><center><font face=arial size=2><b>Last Saved:</b> <?php echo date('h:i:s A'); ?> (PST)</font></center></td>
</tr></table>

<form method=post action=wysiwyg.php>
<input type=hidden name=file value="<?php echo $_GET['file']; ?>">

<textarea name="content" id="editor1">
<?php 
echo htmlspecialchars(file_get_contents($_GET['file'])); 
?>
</textarea>

<script type="text/javascript">
	CKEDITOR.replace( 'editor1' );
</script>

</form>


</body>
</html>

<?php 
}else{

	$fh = fopen($_POST['file'], 'w') or die("<b>HelioPanel is unable to edit the file. Please check file permissions.</b>"); 
	fwrite($fh, $_POST['content']); 
	fclose($fh);  
	header("location:wysiwyg.php?file=".$_POST['file']); 
	
}?>