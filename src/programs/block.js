/**
 * BLOCK: orc-programs
 *
 *  Block for displaying Orchard Recovery Center Programs
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ } = wp.i18n;                  // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

registerBlockType( 'orc/programs', {
	title: __( 'Programs' ),       // Block title.
	icon: 'list-view',             // Block icon from Dashicons.
	category: 'orc-blocks',        // Block category.
	keywords: [
		__( 'ORC' ),
		__( 'Orchard Recovery Center' ),
		__( 'Programs' ),
	],

	supports: {
		align: ['center', 'full'],
		alignWide: true,
		anchor: true,
		customClassName: true,
		html: false,
	},

	edit: ( props ) => {

		// Render the block in the editor.
		return (
			<div className={ props.className }>
				<label>Display Programs</label>
			</div>
		);
	},

	/**
	 * Blocks save function
	 * 
	 * This one returns null since we are using a render function in php
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @returns null Using render function
	 */
	save: () => {
		return null;
	},

} );
