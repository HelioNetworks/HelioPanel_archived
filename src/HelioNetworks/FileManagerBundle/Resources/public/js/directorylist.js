$( '#dialog-box' ).dialog({
	autoOpen: false,
	height: 300,
	width: 350,
	modal: true,
	buttons: {
		'Submit': function() {
			$form = $('#dialog-box').find('form');
			$form.hide();
			$('<div />').html('Loading...').appendTo('#dialog-box');
			$.post($form.attr('action'), $form.serialize(), function () {
				location.reload();
			});
		},
		'Cancel': function() {
			$( this ).dialog( 'close' );
		}
	},
	close: function() {
		allFields.val( '' ).removeClass( 'ui-state-error' );
	}
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
		$('#dialog-box').html('');
		$('<div />').html('Loading...').appendTo('#dialog-box');
		$('#dialog-box').dialog('open');
		
		$('#dialog-box').load($this.attr('form-src'), function () {
			
			$('#dialog-box').find('#current-file').val($this.attr('current-file'));
		});
	});
});