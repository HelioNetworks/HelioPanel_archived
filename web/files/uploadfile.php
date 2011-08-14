<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
	header("location:../login.php");
}

if (!isset($_POST['path'])) {
?>

<html>

<head>
	<link rel="stylesheet" href="../jquery/themes/base/jquery.ui.all.css">
	<script src="../jquery/jquery-1.6.2.js"></script>
	<script src="../jquery/external/jquery.bgiframe-2.1.2.js"></script>
	<script src="../jquery/ui/jquery.ui.core.js"></script>
	<script src="../jquery/ui/jquery.ui.widget.js"></script>
	<script src="../jquery/ui/jquery.ui.mouse.js"></script>
	<script src="../jquery/ui/jquery.ui.button.js"></script>
	<script src="../jquery/ui/jquery.ui.draggable.js"></script>
	<script src="../jquery/ui/jquery.ui.position.js"></script>
	<script src="../jquery/ui/jquery.ui.resizable.js"></script>
	<script src="../jquery/ui/jquery.ui.dialog.js"></script>
	<script src="../jquery/ui/jquery.effects.core.js"></script>
	
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
				"Upload File": function() {
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
	
	<title>Upload File</title>

<body>

<div id="dialog" title="Upload File">

	<form method=post action=uploadfile.php name="form" enctype="multipart/form-data">
	<fieldset>
	<input type=hidden name=path value="<?php echo $_GET['path']; ?>">
	<input type="hidden" name="MAX_FILE_SIZE" value="262144000" />

	<label for="parent">Path to parent</label>
	<input type="text" name="parent" value="<?php echo $_GET['path']; ?>" id="parent" class="text ui-widget-content ui-corner-all" disabled />
	
	<label for="filename">Uploaded File</label>
	<input type=file name=uploadedfile>
			
	</fieldset>
	</form>
	
</div>
	
</body>

</html>
	
<?php
}else{
	
$target_path = $_POST['path'] . basename( $_FILES['uploadedfile']['name']); 

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	header("location:../files.php?path=".$_POST['path']);
}else{
    echo "There was an error uploading the file; please try again.";
}

}