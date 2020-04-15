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
const { InspectorControls } = wp.editor;
const { Fragment }          = wp.element;
const {
	TextControl,
	PanelBody,
	PanelRow 
} = wp.components;

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
		align: [
			'left',
			'center',
			'right'
		],
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
			type: 'string',
			default: '426'
		},
		height: {
			type: 'string',
			default: '240'
		},
		dimensions: {
			type: 'string',
			default: '(426 x 240)'
		}
	},

	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props;
		const {
			width,
			height,
			dimensions
		} = props.attributes;

		// Render the block in the editor.
		return (
			<Fragment>

				<InspectorControls>
					<PanelBody title = { 'Video Dimensions' }>
						<PanelRow>
							<TextControl
								label = 'Video width (px)'
								type = 'number'
								min = '0'
								max = '1920'
								onChange = { ( value ) => {
									setAttributes( {
										width: value,
										dimensions: '(' + value + ' x ' + height + ')'
									} )
								} }
								value = { width }
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Video height (px)'
								type = 'number'
								min = '0'
								max = '1920'
								onChange = { ( value ) => {
									setAttributes( {
										height: value,
										dimensions: '(' + width + ' x ' + value + ')'
									} )
								} }
								value = { height }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display Videos {dimensions}</label>
				</div>

			</Fragment>

		);
	},

	/**
	 * Return null to allow render in a php function.
	 */
	save: ( props ) => {
		return null;
	},

} );
