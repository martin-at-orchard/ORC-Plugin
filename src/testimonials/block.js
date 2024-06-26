/**
 * BLOCK: orc-testimonials
 */

//  Import CSS.
import './editor.scss'
import './style.scss'

import {useState} from 'react'

const { __ }                = wp.i18n
const { registerBlockType } = wp.blocks
const { InspectorControls } = wp.blockEditor
const { Fragment }          = wp.element
const {
	PanelRow,
	PanelBody,
	TextControl,
	RadioControl,
	CheckboxControl
} = wp.components

registerBlockType( 'orc/testimonials', {
	title: __( 'Testimonials' ),   // Block title.
	icon: 'format-status',         // Block icon from Dashicons.
	category: 'orc-blocks',        // Block category.
	keywords: [
		__( 'ORC' ),
		__( 'Orchard Recovery Center' ),
		__( 'Testimonials' )
	],

	supports: {
		align: true,            // same as ['left', 'center', 'right', 'wide', 'full']
		alignWide: true,
		anchor: true,
		customClassName: true,
		html: false
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
		frontPage: {
			type: 'boolean',
			default: false
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
		} = props
		const {
			christmas,
			numPosts,
			frontPage,
			wantLink,
			wantExcerpt,
			wantLocation,
			wantButton,
			buttonText
		} = props.attributes
		const [status, setStatus] = useState( ( '1' === christmas ? '(Christmas - ' : '(Normal - ' ) + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's ' : ' ' )  + ( true === frontPage ? ' - Display on Front Page)' : ')' ) )

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
									( value ) => {
										setAttributes( {
											christmas: value
										})
										setStatus( ( '1' === value ? '(Christmas - ' : '(Normal - ' ) + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's ' : ' ' ) + ( true === frontPage ? ' - Display on Front Page)' : ')' ) )
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
									( value ) => {
										setAttributes( {
											numPosts: value
										} )
										setStatus( ( '1' === christmas ? '(Christmas - ' : '(Normal - ' ) + ( 0 == value ? 'All' : value ) + ' Post' + ( ( 0 == value || value > 1 ) ? 's ' : ' ' ) + ( true === frontPage ? ' - Display on Front Page)' : ')' ) )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title = { 'Front End Display Options' }>
						<PanelRow>
							<CheckboxControl
								label = "Display on Front Page?"
								checked = { frontPage }
								onChange = {
									( value ) => {
										setAttributes( {
											frontPage: value
										} )
										setStatus( ( '1' === christmas ? '(Christmas - ' : '(Normal - ' ) + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's ' : ' ' ) + ( true === value ? ' - Display on Front Page)' : ')' ) )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable link to entire post?"
								checked = { wantLink }
								onChange = {
									( value ) => {
										setAttributes( {
											wantLink: value
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
									( value ) => {
										setAttributes( {
											wantExcerpt: value
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
									( value ) => {
										setAttributes( {
											wantLocation: value
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
									( value ) => {
										setAttributes( {
											wantButton: value
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
									( value ) => {
										setAttributes( {
											buttonText: value
										} )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>
						Display Testimonials {status}
					</label>
				</div>
			</Fragment>
		);
	},

	save: () => {
		return null;
	},

} );
