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
const { InspectorControls } = wp.editor;
const { Fragment }          = wp.element;
const {
	PanelRow,
	PanelBody,
	TextControl,
	CheckboxControl
} = wp.components;

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

	/**
	 * Attributes for the block
	 */
	attributes: {
		displayString: {
			type: 'string',
			default: '(All Posts)'
		},
		numPosts: {
			type: 'string',
			default: '0'
		},
		wantLink: {
			type: 'boolean',
			default: true
		},
		wantExcerpt: {
			type: 'boolean',
			default: true
		},
		wantButton: {
			type: 'boolean',
			default: true
		},
		buttonText: {
			type: 'string',
			default: 'View More'
		}
	},

	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props;
		const {
			displayString,
			numPosts,
			wantLink,
			wantExcerpt,
			wantButton,
			buttonText
		} = props.attributes;

		// Render the block in the editor.
		return (
			<Fragment>
				<InspectorControls style = { { marginBottom: '40px' } }>
					<PanelBody title = { 'Front End Display Options' }>
						<PanelRow>
							<TextControl
								label = 'Number of media posts to display (0 - all)'
								type  = 'number'
								min   = '0'
								max   = '30'
								step  = '1'
								value = { numPosts }
								onChange = {
									( option ) => {
										setAttributes( {
											numPosts: option,
											displayString: '(' + (0 == option ? 'All' : option) + ' Post' + ((0 == option || option > 1) ? 's)' : ')')
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable link to entire post?"
								checked = { wantLink }
								onChange = {
									( option ) => {
										setAttributes( {
											wantLink: option
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Display excerpt?"
								checked = { wantExcerpt }
								onChange = {
									( option ) => {
										setAttributes( {
											wantExcerpt: option
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable View More button?"
								checked = { wantButton }
								onChange = {
									( option ) => {
										setAttributes( {
											wantButton: option
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'View More button text'
								value = { buttonText }
								onChange = {
									( option ) => {
										setAttributes( {
											buttonText: option
										} )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display Media Coverage {displayString}</label>
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
