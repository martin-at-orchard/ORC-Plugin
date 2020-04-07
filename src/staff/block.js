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
	 * Attributes for the block
	 * 
	 * departments        - List of departments
	 * selectedDepartment - Selected department
	 * showHomePage       - Staff should be shown on home page
	 */
	attributes: {
		departments: {
			type: 'object'
		},
		selectedDepartment: {
			type: 'string'
		},
		showHomePage: {
			type: 'string'
		}
	},

	edit: ( props ) => {

		// If we don't have a list of the departments get it.
		if ( ! props.attributes.departments ) {
			wp.apiFetch( {
				url: '/wp-json/wp/v2/orc-departments'
			} ).then( departments => {
				props.setAttributes( {
					departments: departments
				} )
			} );
		}
		
		// No departments returned yet.
		if ( ! props.attributes.departments ) {
			return 'Loading ...';
		}

		// No departments have been created yet.
		if ( props.attributes.departments && 0 === props.attributes.departments.length ) {
			return 'No departments';
		}

		// When the departments select has changed, update the selected department.
		function updateDepartment( e ) {
			props.setAttributes( {
				selectedDepartment: e.target.value
			} );
		}

		// Render the block in the editor.
		return (
			<div className={ props.className }>
				<label>
					Display staff in department: 
				</label>
				<select class="department-select" onChange={ updateDepartment } value={ props.attributes.selectedDepartment }>
					<optgroup label="All">
						<option value="0" key="0">
							All Departments
						</option>
					</optgroup>
					<optgroup label="Departments">
						{
							props.attributes.departments.map( department => {
								return (
									<option value={ department.id } key={ department.id }>
										{ department.name }
									</option>
								)
							} )
						}
					</optgroup>
					<optgroup label="Homepage">
						<option value="-1" key="-1">
							Home Page Only
						</option>
					</optgroup>
				</select>
			</div>
		);
	},

	/**
	 * Return null to allow render in a php function.
	 */
	save: ( props ) => {
		return null;
	},

} );
