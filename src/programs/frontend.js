'use strict';

document.addEventListener('click', function (event) {

	// Check that the clicked element is the one we want
	if ( event.target.matches('.program') ) {
		let span = event.target.childNodes[4];
		if ( 'SPAN' === span.tagName.toUpperCase() ) {
			let link = span.dataset.link;
			window.location.href = link;
		}
	} else if ( event.target.parentElement.matches('.program') ) {
		let span = event.target.parentElement.childNodes[4];
		if ( 'SPAN' === span.tagName.toUpperCase() ) {
			let link = span.dataset.link;
			window.location.href = link;
		}
	}

}, false);
