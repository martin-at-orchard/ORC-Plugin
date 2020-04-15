/**
 * BLOCK: orc-admissions
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 * 
 * Takes:
 *    Block name usually namespace/block
 *    Object for configuring the block
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 */
registerBlockType( 'orc/admissions', {
	title: __( 'Admissions' ),       // Block title.
	icon: 'tickets-alt',             // Block icon from Dashicons.
	category: 'orc-blocks',          // Block category.
	keywords: [
		__( 'ORC' ),
		__( 'Orchard Recovery Center' ),
		__( 'Admissions' ),
	],

	supports: {
		align: true,            // same as ['left', 'center', 'right', 'wide', 'full']
		alignWide: true,
		anchor: true,
		customClassName: true,
		html: false,
	},

	edit: ( props ) => {

		// Render the block in the editor.
		return (
			<div className={ props.className }>
				<label>Display Admissions</label>
			</div>
		);
	},

	save: () => {
		return null;
	},

} );
