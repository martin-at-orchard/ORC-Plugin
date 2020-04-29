/**
 * BLOCK: media-coverage
 *
 * Block for displaying Orchard Recovery Center Media Coverage
 */

//  Import CSS.
import './editor.scss'
import './style.scss'

import {useState} from 'react'

const { __ }                = wp.i18n
const { registerBlockType } = wp.blocks
const { InspectorControls } = wp.editor
const { Fragment }          = wp.element
const {
	PanelRow,
	PanelBody,
	TextControl,
	CheckboxControl
} = wp.components

registerBlockType( 'orc/media-coverage', {
	title:     __( 'Media Coverage' ),
	icon:     'admin-media',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Media Coverage' ),
		__( 'Orchard Recovery Center' )
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
		} = props
		const {
			numPosts,
			wantLink,
			wantExcerpt,
			wantButton,
			buttonText
		} = props.attributes
		const [status, setStatus] = useState( '(' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )

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
									( value ) => {
										setAttributes( {
											numPosts: value
										} )
										setStatus( '(' + ( 0 == value ? 'All' : value ) + ' Post' + ( ( 0 == value || value > 1 ) ? 's)' : ')' ) )
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
					<label>Display Media Coverage {status}</label>
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
