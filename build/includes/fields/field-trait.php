<?php
namespace PRAEF\Fields\Traits;

trait Textoption {

	/**
	 * Get default text option settings
	 *
	 * @param boolean $word_restriction word_restriction.
	 *
	 * @return array
	 */
	public static function get_default_text_option_settings( $word_restriction = false ) {
		$properties = array(
			array(
				'name'       => 'placeholder',
				'title'      => __( 'Placeholder text', 'product-addon-custom-field' ),
				'type'       => 'text',
				'tag_filter' => 'no_fields',
				'section'    => 'advanced',
				'priority'   => 10,
				'help_text'  => __( 'Text for HTML5 placeholder attribute', 'product-addon-custom-field' ),
			),

			array(
				'name'       => 'default',
				'title'      => __( 'Default value', 'product-addon-custom-field' ),
				'type'       => 'text',
				'tag_filter' => 'no_fields',
				'section'    => 'advanced',
				'priority'   => 11,
				'help_text'  => __( 'The default value this field will have', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'size',
				'title'     => __( 'Size', 'product-addon-custom-field' ),
				'type'      => 'text',
				'variation' => 'number',
				'section'   => 'advanced',
				'priority'  => 20,
				'help_text' => __( 'Size of this input field', 'product-addon-custom-field' ),
			),
		);

		if ( $word_restriction ) {
			$properties[] = array(
				'name'      => 'word_restriction',
				'title'     => __( 'Word Restriction', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 15,
				'help_text' => __( 'Numebr of words the author to be restricted in', 'product-addon-custom-field' ),
			);
		}

		return apply_filters( 'prafe_form_builder_common_text_fields_properties', $properties );
	}
}

trait DropDownOption {

	/**
	 * Get default dropdown option settings
	 *
	 * @param boolean $is_multiple is_multiple.
	 *
	 * @return array
	 */
	public function get_default_option_dropdown_settings( $is_multiple = false ) {
		return array(
			'name'        => 'options',
			'title'       => __( 'Options', 'product-addon-custom-field' ),
			'type'        => 'option_data',
			'is_multiple' => $is_multiple,
			'section'     => 'basic',
			'priority'    => 12,
			'help_text'   => __( 'Add options for the form field', 'product-addon-custom-field' ),
		);
	}
}

trait TextareaOption {

	/**
	 * Get default textarea option settings
	 *
	 * @return array
	 */
	public function get_default_textarea_option_settings() {
		return array(
			array(
				'name'      => 'rows',
				'title'     => __( 'Rows', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 10,
				'help_text' => __( 'Number of rows in textarea', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'cols',
				'title'     => __( 'Columns', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 11,
				'help_text' => __( 'Number of columns in textarea', 'product-addon-custom-field' ),
			),

			array(
				'name'         => 'placeholder',
				'title'        => __( 'Placeholder text', 'product-addon-custom-field' ),
				'type'         => 'text',
				'section'      => 'advanced',
				'priority'     => 12,
				'help_text'    => __( 'Text for HTML5 placeholder attribute', 'product-addon-custom-field' ),
				'dependencies' => array(
					'rich' => 'no',
				),
			),

			array(
				'name'      => 'default',
				'title'     => __( 'Default value', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 13,
				'help_text' => __( 'The default value this field will have', 'product-addon-custom-field' ),
			),
		);
	}
}
