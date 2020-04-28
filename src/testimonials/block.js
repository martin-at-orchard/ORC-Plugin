/**
 * BLOCK: orc-testimonials
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
	RadioControl,
	CheckboxControl
} = wp.components;

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
	 */
	attributes: {
		christmas: {
			type: 'string',
			default: '0'
		},
		numPosts: {
			type: 'string',
			default: '0'
		},
		testimonialType: {
			type: 'string',
			default: '(Normal - All Posts)'
		},
		wantLink: {
			type: 'boolean',
			default: true
		},
		wantExcerpt: {
			type: 'boolean',
			default: true
		},
		wantLocation: {
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
			christmas,
			numPosts,
			testimonialType,
			wantLink,
			wantExcerpt,
			wantLocation,
			wantButton,
			buttonText
		} = props.attributes;

		// Render the block in the editor.
		return (
			<Fragment>
				<InspectorControls style = { { marginBottom: '40px' } }>
					<PanelBody title = { 'Testimonial type' }>
						<PanelRow>
							<RadioControl
								label = 'Testimonial type'
								selected = { christmas }
								options = { [
									{ label: 'Display Normal Testimonials', value: '0' },
									{ label: 'Display Christmas Testimonials', value: '1' }
								] }
								onChange = {
									( option ) => {
										let displayString =
											('1' === option ? '(Christmas - ' : '(Normal - ') + (0 == numPosts ? 'All' : numPosts) + ' Post' + ((0 == numPosts || numPosts > 1) ? 's)' : ')')
										setAttributes( {
											christmas: option,
											testimonialType: displayString
										})
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Number of testimonials to display (0 - all)'
								type  = 'number'
								min   = '0'
								max   = '30'
								step  = '1'
								value = { numPosts }
								onChange = {
									( option ) => {
										let displayString =
											('1' === christmas ? '(Christmas - ' : '(Normal - ') + (0 == option ? 'All' : option) + ' Post' + ((0 == option || option > 1) ? 's)' : ')')
										setAttributes( {
											numPosts: option,
											testimonialType: displayString
										} )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title = { 'Front End Display Options' }>
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
								label = "Enable City/Province?"
								checked = { wantLocation }
								onChange = {
									( option ) => {
										setAttributes( {
											wantLocation: option
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
					<label>
						Display Testimonials {testimonialType}
					</label>
				</div>
			</Fragment>
		);
	},

	save: () => {
		return null;
	},

} );
