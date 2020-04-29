/**
 * BLOCK: videos
 *
 * Block for displaying Orchard Recovery Center Media Coverage
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
} = wp.components

registerBlockType( 'orc/videos', {
	title:     __( 'Videos' ),
	icon:     'video-alt',
	category: 'orc-blocks',
	keywords: [
		__( 'ORC' ),
		__( 'Videos' ),
		__( 'Orchard Recovery Center' )
	],

	supports: {
		align: true,            // same as ['left', 'center', 'right', 'wide', 'full']
		anchor: true,
		customClassName: true,
		html: false
	},

	/**
	 * Attributes for the block
	 * 
	 * Width  - Width of video
	 * Height - Height of video
	 */
	attributes: {
		numPosts: {
			type: 'string',
			default: '0'
		},
		width: {
			type: 'string',
			default: '426'
		},
		height: {
			type: 'string',
			default: '240'
		}
	},

	edit: ( props ) => {

		const {
			className,
			setAttributes
		} = props
		const {
			width,
			height,
			numPosts,
		} = props.attributes
		const [status, setStatus] = useState( '(' + width + ' x ' + height + ') (' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )

		// Render the block in the editor.
		return (
			<Fragment>

				<InspectorControls>
					<PanelBody title = { 'Video Dimensions' }>
						<PanelRow>
							<TextControl
								label = 'Video width (px)'
								type = 'number'
								min = '0'
								max = '1920'
								value = { width }
								onChange = { 
									( value ) => {
										setAttributes( {
											width: value
										} )
										setStatus( '(' + value + ' x ' + height + ') (' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )
									}
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label = 'Video height (px)'
								type = 'number'
								min = '0'
								max = '1920'
								value = { height }
								onChange = {
									( value ) => {
										setAttributes( {
											height: value
										} )
										setStatus( '(' + width + ' x ' + value + ') (' + ( 0 == numPosts ? 'All' : numPosts ) + ' Post' + ( ( 0 == numPosts || numPosts > 1 ) ? 's)' : ')' ) )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody title = { 'Front End Display Options' }>
						<PanelRow>
							<TextControl
								label = 'Number of videos to display (0 - all)'
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
										setStatus( '(' + width + ' x ' + height + ') (' + ( 0 == value ? 'All' : value ) + ' Post' + ( ( 0 == value || value > 1 ) ? 's)' : ')' ) )
									}
								}
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>

				<div className={ className }>
					<label>Display Videos {status}</label>
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
