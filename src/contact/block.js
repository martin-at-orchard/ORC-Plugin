/**
 * BLOCK: contact
 *
 * Block for displaying Orchard Recovery Center Contacts
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
	CheckboxControl,
	Dashicon,
} = wp.components

registerBlockType( 'orc/contact', {
	title:     __( 'Contact' ),
	icon:     'phone',
	category: 'orc-blocks',
	description: 'Display the Orchard Recovery Contact Link',
	keywords: [
		__( 'ORC' ),
		__( 'Contact' ),
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
		wantIcon: {
			type: 'boolean',
			default: true
		},
		wantLink: {
			type: 'boolean',
			default: true
		},
		prefix: {
			type: 'string',
			default: ''
		},
		suffix: {
			type: 'string',
			default: ''
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
			setAttributes
		} = props
		const {
			type,
			wantIcon,
			wantLink,
			prefix,
			suffix,
			theClass
		} = props.attributes

		// Create the contacts object
		let contactsObject = {
			'local': 'Local Phone Number',
			'tollfree': 'Toll Free Phone Number',
			'text': 'Text Number',
			'fax': 'Fax Number',
			'intake': 'Orchard Recovery',
			'communications': 'Orchard Communications',
			'hr': 'Orchard Human Resources',
			'alumni': 'Orchard Alumni Coordinator',
			'website': 'Orchard Website Administrator',
			'privacy': 'Privacy Officer'
		}

		// Create the icon object
		let iconObject = {
			'local': <Dashicon icon="phone" />,
			'tollfree': <Dashicon icon="phone" />,
			'text': <Dashicon icon="smartphone" />,
			'fax': <Dashicon icon="phone" />,
			'intake': <Dashicon icon="email-alt2" />,
			'communications': <Dashicon icon="email-alt2" />,
			'hr': <Dashicon icon="email-alt2" />,
			'alumni': <Dashicon icon="email-alt2" />,
			'website': <Dashicon icon="email-alt2" />,
			'privacy': <Dashicon icon="email-alt2" />
		}
		
		// Create the select box for the contact types.
		let options = []
		for ( const [key, label] of Object.entries( contactsObject ) ) {
			options.push( {
				key: key,
				label: label,
				value: key
			} );
		}

		const [icon, setIcon ] = useState( iconObject[type] )
		const [underline, setUnderline] = useState( ( wantLink ) ? 'wp-block-orc-contact underline' : 'wp-block-orc-contact' )
		const [status, setStatus] = useState( prefix + ' ' + contactsObject[type] + ' ' + suffix )

		// Render the block in the editor.
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title = { 'Contact Type' }>
						<PanelRow>
							<SelectControl
								label = 'Contact'
								value = { type }
								options = { options }
								onChange = {
									( value ) => {
										setAttributes( {
											type: value
										})
										setIcon( iconObject[value] )
										setStatus( prefix + ' ' + contactsObject[value] + ' ' + suffix )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Display Icon?"
								checked = { wantIcon }
								onChange = {
									( value ) => {
										setAttributes( {
											wantIcon: value
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Display Link?"
								checked = { wantLink }
								onChange = {
									( value ) => {
										setAttributes( {
											wantLink: value
										} )
										setUnderline( ( value ) ? 'wp-block-orc-contact underline' : 'wp-block-orc-contact' )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Prefix'
								value = { prefix }
								onChange = {
									( value ) => {
										setAttributes( {
											prefix: value
										} )
										setStatus( value + ' ' + contactsObject[type] + ' ' + suffix )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Suffix'
								value = { suffix }
								onChange = {
									( value ) => {
										setAttributes( {
											suffix: value
										} )
										setStatus( prefix + ' ' + contactsObject[type] + ' ' + value )
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

				<div className={ underline }>
					<label>{(wantIcon) ? icon : ''} {status}</label>
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
