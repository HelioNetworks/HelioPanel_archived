$( "#dialog-box" ).dialog({
	autoOpen: false,
	height: 300,
	width: 350,
	modal: true,
	buttons: {
		"Submit": function() {

		},
		Cancel: function() {
			$( this ).dialog( "close" );
		}
	},
	close: function() {
		allFields.val( "" ).removeClass( "ui-state-error" );
	}
});

$( ".modal-form" ).each(function () {
	
	var $this = $(this);
	$this.click(function () {
		$('#dialog-box').html('Loading...');
		$('#dialog-box').dialog('open');
		
		$('#dialog-box').load($this.attr('form-src'), function () {
			
			$('#dialog-box').find('#current-file').val($this.attr('current-file'));
		});
	});
});