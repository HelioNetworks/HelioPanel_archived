<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

require __DIR__.'/../init.php';

if (!isset($_POST['path'])) {
?>

<html>

<head>
	<link rel="stylesheet" href="../jquery-optimized/css/ui-lightness/jquery-ui-1.8.15.custom.css">
	<script src="../jquery-optimized/js/jquery-1.6.2.min.js"></script>
	<script src="../jquery-optimized/js/jquery-ui-1.8.15.custom.min.js"></script>

	<style>
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
	</style>

	<script>
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( "#dialog" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
			buttons: {
				"Confirm Delete": function() {
					document.forms["form"].submit();
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				history.go(-1);
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#button" )
				$( "#dialog" ).dialog( "open" );
	});
	</script>

	<title>Delete</title>
</head>

<body>

<div id="dialog" title="Delete File">

	<p class="validateTips">Please confirm that you wish to delete this file:</p>

	<form method=post action=delete.php name="form">
	<fieldset>
	<input type=hidden name=path value="<?php echo $_GET['file']; ?>">
	<input type=hidden name=file value="<?php echo $_GET['file']; ?>">

	<label for="file2">File Path</label>
	<input type="text" name="file2" value="<?php echo $_GET['path']."".$_GET['file']; ?>" id="file2" class="text ui-widget-content ui-corner-all" disabled />

	</fieldset>
	</form>

</div>

</body>

</html>

<?php
}else{

	$fileRepository->rm($_POST['path']."".$_POST['file']);

	header("location:../files.php?path=".$_POST['path']);
}