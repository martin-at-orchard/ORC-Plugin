/**
 * BLOCK: staff
 *
 * Block for displaying Orchard Recovery Center Staff
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
	SelectControl,
	PanelBody,
	PanelRow,
	TextControl,
	CheckboxControl
} = wp.components

registerBlockType( 'orc/staff', {
	title:     __( 'ORC Staff' ),
	icon:     'groups',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Staff' ),
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
	 * Attributes for the block.
	 */
	attributes: {
		selectedDepartment: {
			type: 'string',
			default: '0'
		},
		displayString: {
			type: 'string',
			default: 'All Staff (All Posts)'
		},
		numPosts: {
			type: 'string',
			default: '0'
		},
		wantPosition: {
			type: 'boolean',
			default: true
		},
		wantQualifications: {
			type: 'boolean',
			default: true
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

	/**
	 * Backend editor.
	 */
	edit: ( props ) => {

		const {
			setAttributes,
			className,
		} = props
		const {
			selectedDepartment,
			numPosts,
			wantPosition,
			wantQualifications,
			wantLink,
			wantExcerpt,
			wantButton,
			buttonText
		} = props.attributes

		// Create the select box for the staff departments.
		// departments are passed in through wp_localize_script from the php code
		let departmentNames = [];
		let options = [{
			key: 'all',
			label: 'All Staff',
			value: 0
		}];
		departmentNames[0] = 'All Staff';
		options.push( {
			key: 'home',
			label: 'Home Page Staff ONLY',
			value: -1
		} );
		departmentNames[-1] = 'Home Page Staff ONLY';
		for ( const D of departments ) {
			options.push( {
				key: D.key,
				label: D.label,
				value: D.value
			} );
			departmentNames[D.value] = D.label;
		}

		const [status, setStatus] = useState( departmentNames[selectedDepartment] + ' (' + (0 == numPosts ? 'All' : numPosts) + ' Post' + ((0 == numPosts || numPosts > 1) ? 's)' : ')') )

		// Render the block in the editor.
		return (
			<Fragment>
				<InspectorControls>
					<PanelBody title = { 'Staff Display type' }>
						<PanelRow>
							<SelectControl
								label = 'Staff to display'
								value = { selectedDepartment }
								options = { options }
								onChange = {
									( value ) => {
										setAttributes( {
											selectedDepartment: value
										})
										setStatus( departmentNames[value] + ' (' + (0 == numPosts ? 'All' : numPosts) + ' Post' + ((0 == numPosts || numPosts > 1) ? 's)' : ')') )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title = { 'Front End Display Options' }>
						<PanelRow>
							<TextControl
								label = 'Number of staff members to display (0 - all)'
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
										setStatus( departmentNames[selectedDepartment] + ' (' + (0 == value ? 'All' : value) + ' Post' + ((0 == value || value > 1) ? 's)' : ')') )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable position display?"
								checked = { wantPosition }
								onChange = {
									( value ) => {
										setAttributes( {
											wantPosition: value
										} )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<CheckboxControl
								label = "Enable qualifications display?"
								checked = { wantQualifications }
								onChange = {
									( value ) => {
										setAttributes( {
											wantQualifications: value
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
					<label>Display {status}</label>
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
