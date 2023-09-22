<?php
namespace WCPRAEF\Fields\Traits;

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
				'title'      => __( 'Placeholder text', 'wc-product-addon-custom-field' ),
				'type'       => 'text',
				'tag_filter' => 'no_fields',
				'section'    => 'advanced',
				'priority'   => 10,
				'help_text'  => __( 'Text for HTML5 placeholder attribute', 'wc-product-addon-custom-field' ),
			),

			array(
				'name'       => 'default',
				'title'      => __( 'Default value', 'wc-product-addon-custom-field' ),
				'type'       => 'text',
				'tag_filter' => 'no_fields',
				'section'    => 'advanced',
				'priority'   => 11,
				'help_text'  => __( 'The default value this field will have', 'wc-product-addon-custom-field' ),
			),

			array(
				'name'      => 'size',
				'title'     => __( 'Size', 'wc-product-addon-custom-field' ),
				'type'      => 'text',
				'variation' => 'number',
				'section'   => 'advanced',
				'priority'  => 20,
				'help_text' => __( 'Size of this input field', 'wc-product-addon-custom-field' ),
			),
		);

		if ( $word_restriction ) {
			$properties[] = array(
				'name'      => 'word_restriction',
				'title'     => __( 'Word Restriction', 'wc-product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 15,
				'help_text' => __( 'Numebr of words the author to be restricted in', 'wc-product-addon-custom-field' ),
			);
		}

		return apply_filters( 'contactum_form_builder_common_text_fields_properties', $properties );
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
			'title'       => __( 'Options', 'wc-product-addon-custom-field' ),
			'type'        => 'option_data',
			'is_multiple' => $is_multiple,
			'section'     => 'basic',
			'priority'    => 12,
			'help_text'   => __( 'Add options for the form field', 'wc-product-addon-custom-field' ),
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
				'title'     => __( 'Rows', 'wc-product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 10,
				'help_text' => __( 'Number of rows in textarea', 'wc-product-addon-custom-field' ),
			),

			array(
				'name'      => 'cols',
				'title'     => __( 'Columns', 'wc-product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 11,
				'help_text' => __( 'Number of columns in textarea', 'wc-product-addon-custom-field' ),
			),

			array(
				'name'         => 'placeholder',
				'title'        => __( 'Placeholder text', 'wc-product-addon-custom-field' ),
				'type'         => 'text',
				'section'      => 'advanced',
				'priority'     => 12,
				'help_text'    => __( 'Text for HTML5 placeholder attribute', 'wc-product-addon-custom-field' ),
				'dependencies' => array(
					'rich' => 'no',
				),
			),

			array(
				'name'      => 'default',
				'title'     => __( 'Default value', 'wc-product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 13,
				'help_text' => __( 'The default value this field will have', 'wc-product-addon-custom-field' ),
			),
		);
	}
}
