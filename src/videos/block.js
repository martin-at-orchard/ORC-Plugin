/**
 * BLOCK: videos
 *
 * Block for displaying Orchard Recovery Center Media Coverage
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ }                = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType( 'orc/videos', {
	title:     __( 'ORC Videos' ),
	icon:     'video-alt',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Videos' ),
		__( 'Orchard Recovery Center' ),
	],

	supports: {
		align: ['left', 'center', 'right'],
		anchor: true,
		customClassName: true,
		html: false,
	},

	/**
	 * Attributes for the block
	 * 
	 * Width  - Width of video
	 * Height - Height of video
	 */
	attributes: {
		width: {
			type: 'string'
		},
		height: {
			type: 'string'
		}
	},

	edit: ( props ) => {
		// When the width has changed, update the attribute.
		function updateWidth( e ) {
			props.setAttributes( {
				width: e.target.value
			} );
		}

		// When the height has changed, update the attribute.
		function updateHeight( e ) {
			props.setAttributes( {
				height: e.target.value
			} );
		}

		let width = 320;		// Default width
		if ( props.attributes.width ) {
			width = props.attributes.width;
		}

		let height = 240;		// Default height
		if ( props.attributes.height ) {
			height = props.attributes.height;
		}

		// Render the block in the editor.
		return (
			<div className={ props.className }>
				<label>
					Display Videos
				</label>
				<br />
				<label>
					Video width (px): 
				</label>
				<input type="number" class="video-width" onChange={ updateWidth } value={ width } min="100" max="1920" step="1" />
				<br />
				<label>
					Video height (px): 
				</label>
				<input type="number" class="video-height" onChange={ updateHeight } value={ height } min="100" max="1920" step="1" />
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
