/**
 * Opens web-based share dialogs (Facebook, X, Pinterest) in a small,
 * centred popup window instead of a full new tab - mailto: and WhatsApp
 * links are left to navigate normally.
 *
 * @package lsx-sharing
 */
( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		var link = event.target.closest( '.lsx-sharing-popup' );

		if ( ! link ) {
			return;
		}

		event.preventDefault();

		var width = 580;
		var height = 400;
		var left = window.screenX + ( window.outerWidth - width ) / 2;
		var top = window.screenY + ( window.outerHeight - height ) / 2;

		window.open(
			link.href,
			'lsx-sharing-popup',
			'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top +
				',menubar=no,toolbar=no,location=no,status=no,resizable=yes,scrollbars=yes'
		);
	} );
} )();
