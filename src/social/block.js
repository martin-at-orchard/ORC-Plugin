/**
 * BLOCK: contact
 *
 * Block for displaying Orchard Recovery Center Social Links
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
	SelectControl,
	PanelBody,
	PanelRow,
	TextControl,
} = wp.components

registerBlockType( 'orc/social', {
	title:     __( 'Social' ),
	icon:     'facebook',
	category: 'orc-blocks',
	description: 'Display the Orchard Recovery Center Social Link',
	keywords: [
		__( 'ORC' ),
		__( 'Social' ),
		__( 'Orchard Recovery Center' )
	],

	supports: {
		align: ['center'],
		anchor: true,
		customClassName: true,
		html: false
	},

	/**
	 * Attributes for the block.
	 */
	attributes: {
		type: {
			type: 'string',
			default: 'local'
		},
		theClass: {
			type: 'string',
			default: ''
		}
	},

	/**
	 * Backend editor.
	 */
	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props
		const {
			type,
			theClass
		} = props.attributes

		// Create the select object
		let typesObject = {
			'facebook': 'Facebook',
			'instagram': 'Instagram',
			'twitter': 'Twitter',
			'youtube': 'YouTube'
		}

		// Create the select box for the social links.
		let options = []
		for ( const [key, label] of Object.entries( typesObject ) ) {
			options.push( {
				key: key,
				label: label,
				value: key
			} );
		}

		const [status, setStatus] = useState( typesObject[type] )

		// Render the block in the editor.
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title = { 'Social Link Type' }>
						<PanelRow>
							<SelectControl
								label = 'Social Link'
								value = { type }
								options = { options }
								onChange = {
									( value ) => {
										setAttributes( {
											type: value
										})
										setStatus( typesObject[value] )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Class'
								value = { theClass }
								onChange = {
									( value ) => {
										setAttributes( {
											theClass: value
										} )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display {status} Link</label>
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
