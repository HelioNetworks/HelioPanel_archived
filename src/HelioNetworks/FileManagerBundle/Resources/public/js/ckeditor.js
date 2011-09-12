$(document).ready(function () {
	var editor = $('#editor').ckeditor();

    editor.val($('#file_data').val());

    $('#file_edit').submit(function () {
		$('#file_data').val(editor.val());

		return true;
	});
});