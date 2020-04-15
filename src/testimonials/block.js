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
	RadioControl,
	PanelBody,
	PanelRow 
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
		testimonialType: {
			type: 'string',
			default: '(All)'
		}
	},

	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props;
		const {
			christmas,
			testimonialType
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
									{ label: 'Display Normal Testimonial', value: '0' },
									{ label: 'Display Christmas Testimonial', value: '1' }
								] }
								onChange = {
									( option ) => {
										setAttributes( {
											christmas: option,
											testimonialType: ('0' === option) ? '(All)' : '(Christmas)'
										})
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
