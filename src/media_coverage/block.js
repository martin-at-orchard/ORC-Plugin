/**
 * BLOCK: media-coverage
 *
 * Block for displaying Orchard Recovery Center Media Coverage
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ }                = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType( 'orc/media-coverage', {
	title:     __( 'ORC Media Coverage' ),
	icon:     'admin-media',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Media Coverage' ),
		__( 'Orchard Recovery Center' ),
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
				<label>
					Display Media Coverage
				</label>
			</div>
		);
	},

	/**
	 * Return null to allow render in a php function.
	 */
	save: ( props ) => {
		return null;
	},

} );