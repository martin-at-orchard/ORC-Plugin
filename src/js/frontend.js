'use strict';

/**
 * Check to see if the program or the staff objects are clicked or
 * if a child of the object is clicked.
 */
document.addEventListener('click', function (event) {

	let obj_match    = event.target.matches( '.program' ) || event.target.matches( '.staff' );
	let parent_match = event.target.parentElement.matches( '.program' ) || event.target.parentElement.matches( '.staff' );

	if ( obj_match || parent_match ) {
		let span = '';
		if ( obj_match ) {
			span = event.target.childNodes[0];
		} else {
			span = event.target.parentElement.childNodes[0];
		}

		if ( 'SPAN' === span.tagName.toUpperCase() ) {
			let link = span.dataset.link;
			window.location.href = link;
		}
	}

}, false);
