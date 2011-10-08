$( '#dialog-box' ).dialog({
	autoOpen: false,
	height: 300,
	width: 350,
	modal: true,
	buttons: {
		'Submit': function() {
			$.blockUI();
			$('#dialog-box').dialog('close');
			$form = $('#dialog-box').find('form');
			$.post($form.attr('action'), $form.serialize(), function () {
				location.reload();
			});
		},
		'Cancel': function() {
			$( this ).dialog( 'close' );
		}
	},
});

$('.edit-file').each(function () {
	var $edit = $(this);
	$edit.children('font').children('a').click(function () {
		$edit.find('.edit-file-choices').dialog({
			height: 140,
			modal: true,
		});
	});
});

$( '.modal-form' ).each(function () {
	
	var $this = $(this);
	$this.click(function () {
		$.blockUI();
		$('#dialog-box').html('');		
		$('#dialog-box').load($this.attr('form-src'), function () {
			
			$('#dialog-box').find('#current-file').val($this.attr('current-file'));
			$('#dialog-box').dialog('open');
			$.unblockUI();
		});
	});
});