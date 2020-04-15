/**
 * BLOCK: staff
 *
 * Block for displaying Orchard Recovery Center Staff
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const { __ }                = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { Fragment }          = wp.element;
const {
	SelectControl,
	PanelBody,
	PanelRow 
} = wp.components;

registerBlockType( 'orc/staff', {
	title:     __( 'ORC Staff' ),
	icon:     'groups',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Staff' ),
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
	 * Attributes for the block.
	 */
	attributes: {
		selectedDepartment: {
			type: 'string',
			default: '0'
		},
		departmentName: {
			type: 'string',
			default: 'All Staff'
		}
	},

	/**
	 * Backend editor.
	 */
	edit: ( props ) => {

		const {
			setAttributes,
			className,
		} = props;
		const {
			selectedDepartment,
			departmentName
		} = props.attributes;

		// Create the select box for the staff departments.
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
									( option ) => {
										setAttributes( {
											selectedDepartment: option,
											departmentName: departmentNames[option]
										})
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display {departmentName}</label>
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
