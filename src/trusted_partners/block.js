/**
 * BLOCK: orc-programs
 *
 *  Block for displaying Orchard Recovery Center Tours
 */

//  Import CSS.
import './editor.scss'
import './style.scss'

import {useState} from 'react'

const { __ } = wp.i18n
const { registerBlockType } = wp.blocks
const { InspectorControls } = wp.editor
const { Fragment }          = wp.element
const {
	PanelRow,
	PanelBody,
	TextControl,
	CheckboxControl
} = wp.components

registerBlockType( 'orc/partners', {
	title: __( 'Partners' ),       // Block title.
	icon: 'thumbs-up',         // Block icon from Dashicons.
	category: 'orc-blocks',     // Block category.
	keywords: [
		__( 'ORC' ),
		__( 'Orchard Recovery Center' ),
		__( 'Trusted Partners' )
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
		width: {
			type: 'string',
			default: '200'
		},
		numPosts: {
			type: 'string',
			default: '0'
		},
		wantLink: {
			type: 'boolean',
			default: true
		}
	},

	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props
		const {
			width,
			numPosts,
			wantLink
		} = props.attributes
		const [status, setStatus] = useState( '(' + width + 'px) (' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )
	
		// Render the block in the editor.
		return (
			<Fragment>

				<InspectorControls style = { { marginBottom: '40px' } }>
					<PanelBody title = { 'Front End Display Options' }>
						<PanelRow>
							<TextControl
								label = 'Number of trusted partners to display (0 - all)'
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
										setStatus( '(' + width + 'px) (' + ( 0 == value ? 'All' : value ) + ' Post' + ( ( 0 == value || value > 1 ) ? 's)' : ')' ) )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Image width (px)'
								type = 'number'
								min = '0'
								max = '1920'
								value = { width }
								onChange = {
									( value ) => {
										setAttributes( {
											width: value
										} )
										setStatus( '(' + value + 'px) (' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable link to trusted partner?"
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
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display Trusted Partners {status}</label>
				</div>

			</Fragment>
		)
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
