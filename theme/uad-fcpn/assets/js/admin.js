(function ($) {
	'use strict';

	$(function () {
		let frame;
		const input = $('#uad_document_pdf_id');
		const preview = $('#uad_pdf_preview');
		const removeButton = $('#uad_remove_pdf');

		$('#uad_select_pdf').on('click', function (event) {
			event.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: window.uadAdmin.title,
				button: { text: window.uadAdmin.button },
				library: { type: 'application/pdf' },
				multiple: false,
			});

			frame.on('select', function () {
				const attachment = frame.state().get('selection').first().toJSON();
				input.val(attachment.id);
				preview.empty().append(
					$('<a>', {
						href: attachment.url,
						target: '_blank',
						rel: 'noopener',
						text: attachment.filename,
					})
				);
				removeButton.prop('hidden', false);
			});

			frame.open();
		});

		removeButton.on('click', function (event) {
			event.preventDefault();
			input.val('');
			preview.empty();
			removeButton.prop('hidden', true);
		});
	});
})(jQuery);

