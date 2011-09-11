$(document).ready(function () {
	var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    var Mode = require("ace/mode/php").Mode;
    editor.getSession().setMode(new Mode());

    window.ace = editor;

    editor.getSession().setValue($('#file_data').val());

    $('#file_edit').submit(function () {
		$('#file_data').val(editor.getSession().getValue());

		return true;
	});
});