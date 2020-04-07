/**
 * BLOCK: orc-testimonials
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ }                = wp.i18n;   // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

registerBlockType( 'orc/testimonials', {
	title: __( 'Testimonials' ),   // Block title.
	icon: 'format-status',         // Block icon from Dashicons.
	category: 'orc-blocks',        // Block category.
	keywords: [
		__( 'ORC' ),
		__( 'Orchard Recovery Center' ),
		__( 'Testimonials' ),
	],

	supports: {
		align: true,            // same as ['left', 'center', 'right', 'wide', 'full']
		alignWide: true,
		anchor: true,
		customClassName: true,
		html: false,
	},

	/**
	 * Attributes for the block
	 * 
	 * christmas     - Christmas testimonial
	 */
	attributes: {
		christmas: {
			type: 'string'
		}
	},

	edit: ( props ) => {

		// When the christmas checkbox has changed, update.
		function updateChristmas( ) {
			
			if( '1' === props.attributes.christmas ) {
				props.setAttributes( { christmas: '0' } );
			} else {
				props.setAttributes( { christmas: '1' } );
			}
			
		}

		let checked = '1' === props.attributes.christmas ? 'checked' : ''

		// Render the block in the editor.
		return (
			<div className={ props.className }>
				<label>
					<input type="checkbox" checked={ checked } value="1" onChange={ updateChristmas } />
					Display Christmas Testimonials
				</label>

			</div>
		);
	},

	save: () => {
		return null;
	},

} );
