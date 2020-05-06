<?php // phpcs:ignore

namespace ORC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;     // Exit if accessed directly.
}

/**
 * Class to handle all the option fields for the website
 *
 * @since 0.5.0
 * @package ORC
 */
class Fields {

	/**
	 * Display a text field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function text( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
			),
			$args
		);

		$classes     = ( '' === $args['classes'] ) ? '' : 'class="' . esc_attr( $args['classes'] ) . '"';
		$description = ( '' === $args['description'] ) ? '' : '<span class="description"> ' . esc_attr( $args['description'] ) . '</span>';

		printf(
			'<input type="text" %s name="%s" id="%s" value="%s" />%s',
			$classes,                       // phpcs:ignore
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] ),
			$description                    // phpcs:ignore
		);

	}

	/**
	 * Display a text area field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function text_area( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
				'cols'        => '100',
				'rows'        => '4',
				'style'       => 'style="font-family:Courier New;"',
			),
			$args
		);

		$classes     = ( '' === $args['classes'] ) ? '' : 'class="' . esc_attr( $args['classes'] ) . '"';
		$description = ( '' === $args['description'] ) ? '' : '<span class="description"> ' . esc_attr( $args['description'] ) . '</span>';

		$val = str_replace(
			'\n',
			'',
			$args['value']
		);
		printf(
			'<textarea %s name="%s" id="%s" rows="%s" cols="%s" %s>%s</textarea>%s',
			classes,                        // phpcs:ignore
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['rows'] ),
			esc_attr( $args['cols'] ),
			esc_attr( $args['style'] ),
			esc_attr( $val ),
			$description                    // phpcs:ignore
		);

	}

	/**
	 * Display a number field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function number( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'value'       => '',
				'description' => '',
				'min'         => '0',
				'max'         => '200',
			),
			$args
		);

		$classes     = ( '' === $args['classes'] ) ? '' : 'class="' . esc_attr( $args['classes'] ) . '"';
		$description = ( '' === $args['description'] ) ? '' : '<span class="description"> ' . esc_attr( $args['description'] ) . '</span>';

		printf(
			'<input type="number" min="%s" max="%s" %s name="%s" id="%s" value="%s" />%s',
			esc_attr( $args['min'] ),
			esc_attr( $args['max'] ),
			$classes,                       // phpcs:ignore
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['value'] ),
			$description                    // phpcs:ignore
		);

	}

	/**
	 * Display a checkbox field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function checkbox( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'id'          => '',
				'description' => '',
				'checked'     => '',
			),
			$args
		);

		printf(
			'<input type="checkbox" class="%s" name="%s" id="%s" value="1" %s /><span class="description"> %s</span>',
			esc_attr( $args['classes'] ),
			esc_attr( $args['name'] ),
			esc_attr( $args['id'] ),
			esc_attr( $args['checked'] ),
			esc_attr( $args['description'] )
		);

	}

	/**
	 * Display a radio button field in the form.
	 *
	 * @param array $args The arguments passed to the function.
	 */
	public function radio( $args ) {

		$args = shortcode_atts(
			array(
				'classes'     => '',
				'name'        => '',
				'description' => '',
				'value'       => '',
				'options'     => array(),
			),
			$args
		);

		if ( count( $args['options'] ) > 0 ) {
			foreach ( $args['options'] as $id => $value ) {
				$checked = ( $id === $args['value'] ) ? 'checked' : '';
				printf(
					'<input type="radio" class="%s" name="%s" id="%s" value="%s" %s /><label for="%s">%s</label><br>',
					esc_attr( $args['classes'] ),
					esc_attr( $args['name'] ),
					esc_attr( $id ),
					esc_attr( $id ),
					esc_attr( $checked ),
					esc_attr( $id ),
					esc_attr( $value ),
				);
			}
			printf( 
				'<span class="description"> %s</span>',
				esc_attr( $args['description'] )
			);
		}
	}

}
