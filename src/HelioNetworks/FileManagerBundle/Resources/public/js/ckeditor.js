$(document).ready(function () {
	$('#editor').ckeditor(function () {
	
		$('#editor').val($('#file_data').val());
		
		$('#file_edit').submit(function () {
			$('#file_data').val($('#editor').val());
	
			return true;
		});
	});
});