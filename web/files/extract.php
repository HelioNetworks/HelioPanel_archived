<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

require __DIR__.'/../init.php';

if (!isset($_POST['type'])) {
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
				"Extract Archive": function() {
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

	<title>Extract</title>
</head>

<body>

<div id="dialog" title="Extract Archive">

	<p class="validateTips">Please confirm that you wish to delete this file:</p>

	<form method=post action=extract.php name="form">
	<fieldset>
	<input type=hidden name=path value="<?php echo $_GET['file']; ?>">
	<input type=hidden name=file value="<?php echo $_GET['file']; ?>">

	<label for="file2">Archive Path</label>
	<input type="text" name="file2" value="<?php echo $_GET['path']."".$_GET['file']; ?>" id="file2" class="text ui-widget-content ui-corner-all" disabled />

	<label for="type">Type of Archive</label>
	<select name="type" id="type" class="text ui-widget-content ui-corner-all">
	<option value=zip>ZIP Archive</option>
	<option value=gz>GZ Archive</option>
	<option value=tar>TAR Archive</option>
	</select>

	</fieldset>
	</form>

</div>

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

		$gun = gzopen($file, "r");
		$gzdata = gzread($gun, 500000);
		gzclose($gun);

		file_put_contents($fn, $gzdata);
		rename($fn, $_POST['path']."".$fn);

	}elseif ($_POST['type'] == 'tar') {

		require 'tarfunction.php';
		untar($_POST['path']."".$_POST['file'], $_POST['path']);

	}

	header("location:../files.php?path=".$_POST['path']);

}