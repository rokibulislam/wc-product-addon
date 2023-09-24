<?php
/**
 * Field Checkbox
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF\Fields;

/**
 * WCPRAEF Field class
 *
 * @package WC_Product_Addon_Extra_Field
 * @author  Rokibul
 */
abstract class Base_Field {

	/**
	 * Name of field
	 *
	 * @var string $name name.
	 */
	protected $name = '';

	/**
	 * Input type
	 *
	 * @var string $input_type  input_type.
	 */
	protected $input_type = '';

	/**
	 * Icon
	 *
	 * @var string $icon  icon.
	 */
	protected $icon = 'header';

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->input_type;
	}

	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int   $form_id form_id.
	 *
	 * @return void
	 */
	abstract public function render( $field_settings, $form_id );

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	abstract public function get_options_settings();

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	abstract public function get_field_props();

	/**
	 * Set full width
	 *
	 * @return boolean
	 */
	public function is_full_width() {
		return false;
	}

	/**
	 * Get validator
	 *
	 * @return boolean
	 */
	public function get_validator() {
		return false;
	}

	/**
	 * Get settings
	 *
	 * @return array
	 */
	public function get_js_settings() {

		$settings = array(
			'template'      => $this->get_type(),
			'title'         => $this->get_name(),
			'icon'          => $this->get_icon(),
			'settings'      => $this->get_options_settings(),
			'field_props'   => $this->get_field_props(),
			'is_full_width' => $this->is_full_width(),
		);

	   	if ( $validator = $this->get_validator() ) {
			$settings['validator'] = $validator;
		}
        
		return apply_filters( 'Base_Field_get_js_settings', $settings );
	}

	/**
	 * Get default conditional properties
	 *
	 * @return array
	 */
	public function default_conditional_prop() {
		return array(
			'condition_status' => 'no',
			'cond_field'       => array(),
			'cond_operator'    => array( '=' ),
			'cond_option'      => array( __( '- select -', 'product-addon-custom-field' ) ),
			'cond_logic'       => 'all',
		);
	}

	/**
	 * Get default attribute properties
	 *
	 * @return array
	 */
	public function default_attributes() {
		return array(
			'template'    => $this->get_type(),
			'name'        => '',
			'label'       => $this->get_name(),
			'required'    => 'no',
			'message'     => 'this field is required',
			'id'          => 0,
			'width'       => 'large',
			'css'         => '',
			'placeholder' => '',
			'default'     => '',
			'size'        => 40,
			'help'        => '',
			'is_meta'     => 'yes',
			'is_new'      => true,
		);
	}

	/**
	 * Get default option settings
	 *
	 * @param boolean $is_meta is_meta.
	 * @param boolean $exclude exclude.
	 *
	 * @return array
	 */
	public static function get_default_option_settings( $is_meta = true, $exclude = array() ) {
		$common_properties = array(
			array(
				'name'      => 'label',
				'title'     => __( 'Field Label', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Enter a title of this field', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'help',
				'title'     => __( 'Help text', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 20,
				'help_text' => __( 'Give the user some information about this field', 'product-addon-custom-field' ),
			),

			array(
				'name'      => 'required',
				'title'     => __( 'Required', 'product-addon-custom-field' ),
				'type'      => 'required',
				'options'   => array(
					'yes' => __( 'Yes', 'product-addon-custom-field' ),
					'no'  => __( 'No', 'product-addon-custom-field' ),
				),
				'section'   => 'basic',
				'priority'  => 21,
				'default'   => 'no',
				'inline'    => true,
				'message'   => __( 'This Field is Required', 'product-addon-custom-field' ),
				'help_text' => __( 'Check this option to mark the field required. A form will not submit unless all required fields are provided.', 'product-addon-custom-field' ),
			),

			array(
				'name'     => 'width',
				'title'    => __( 'Field Size', 'product-addon-custom-field' ),
				'type'     => 'radio',
				'options'  => array(
					'small'  => __( 'Small', 'product-addon-custom-field' ),
					'medium' => __( 'Medium', 'product-addon-custom-field' ),
					'large'  => __( 'Large', 'product-addon-custom-field' ),
				),
				'section'  => 'advanced',
				'priority' => 21,
				'default'  => 'large',
				'inline'   => true,
			),

			array(
				'name'      => 'css',
				'title'     => __( 'CSS Class Name', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 22,
				'help_text' => __( 'Provide a container class name for this field. Available classes: product-addon-custom-field-col-half, product-addon-custom-field-col-half-last, product-addon-custom-field-col-one-third, product-addon-custom-field-col-one-third-last', 'product-addon-custom-field' ),
			),
		);
		if ( $is_meta ) {
			$common_properties[] = array(
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Name of the meta key this field will save to', 'product-addon-custom-field' ),
			);
		}

		if ( count( $exclude ) ) {
			foreach ( $common_properties as $key => &$option ) {
				if ( in_array( $option['name'], $exclude ) ) {
					unset( $common_properties[ $key ] );
				}
			}
		}

		return $common_properties;
	}

	/**
	 * Get conditional field
	 *
	 * @return array
	 */
	public function get_conditional_field() {
		return array(
			'name'      => 'wcprafe_cond',
			'title'     => __( 'Conditional Logic', 'product-addon-custom-field' ),
			'type'      => 'conditional_logic',
			'section'   => 'advanced',
			'priority'  => 30,
			'help_text' => '',
		);
	}

	/**
	 * Print label
	 *
	 * @param object $field field.
	 * @param int    $form_id form_id.
	 *
	 * @return void
	 */
	public function print_label( $field, $form_id = 0 ) {
		?>
		<div class="wcprafe-label"> <label for="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) . '_' . esc_attr( $form_id ) : 'cls'; ?>">
		<?php echo esc_html( $field['label'] ) . wp_kses_post( $this->required( $field ) ); ?> </label> </div>
		<?php
	}

	/**
	 * Print list attribute
	 *
	 * @param object $field field.
	 *
	 * @return void
	 */
	public function print_list_attributes( $field ) {
		$label      = isset( $field['label'] ) ? $field['label'] : '';
		$el_name    = ! empty( $field['name'] ) ? $field['name'] : '';
		$class_name = ! empty( $field['css'] ) ? ' ' . $field['css'] : '';
		$field_size = ! empty( $field['width'] ) ? ' field-size-' . $field['width'] : '';
		$message    = ! empty( $field['message'] ) ? $field['message'] : '';

		printf(
			'class="wcprafe-el wcprafe-%s%s%s" data-label="%s" data-errormessage="%s"',
			esc_attr( $el_name ),
			esc_attr( $class_name ),
			esc_attr( $field_size ),
			esc_attr( $label ),
            esc_attr( $message ),
		);
	}

	/**
	 * Required
	 *
	 * @param object $field field.
	 *
	 * @return string
	 */
	public function required( $field ) {
		if ( isset( $field['required'] ) && ( $field['required'] === 'yes' || $field['required'] === true ) ) {
			return '<span class="required">*</span>';
		}
	}

	/**
	 * Get help string
	 *
	 * @param object $field field.
	 *
	 * @return string
	 */
	public function help_text( $field ) {
		if ( empty( $field['help'] ) ) {
			return;
		}
		?>
		<span class="wcprafe-help"><?php echo esc_attr( $field['help'] ); ?></span>
		<?php
	}

	/**
	 * Prepare entry
	 *
	 * @param array $field field.
	 * @param array $post_data post_data.
	 *
	 * @return string
	 */
	public function prepare_entry( $field, $post_data = array() ) {
		$value = ! empty( $post_data[ $field['name'] ] ) ? $post_data[ $field['name'] ] : '';

		if ( is_array( $value ) ) {
			$entry_value = implode( WCPRAEF_SEPARATOR, $value );
		} else {
			$entry_value = trim( $value );
		}

		return $entry_value;
	}

	/**
	 * Conditional logic
	 *
	 * @param array $form_field form_field.
	 * @param int   $form_id form_id.
	 *
	 * @return string | void
	 */
	public function conditional_logic( $form_field, $form_id ) {
		if ( ! isset( $form_field['wcprafe_cond']['condition_status'] ) || $form_field['wcprafe_cond']['condition_status'] !== 'yes' ) {
			return;
		}

		$cond_inputs                     = $form_field['wcprafe_cond'];
		$cond_inputs['condition_status'] = isset( $cond_inputs['condition_status'] ) ? $cond_inputs['condition_status'] : '';

		if ( $cond_inputs['condition_status'] === 'yes' ) {
			$cond_inputs['type']    = $form_field['template'];
			$cond_inputs['name']    = $form_field['name'];
			$cond_inputs['form_id'] = $form_id;
			$condition              = wp_json_encode( $cond_inputs );
		} else {
			$condition = '';
		}
			$script = "wcprafe_conditional_items.push({$condition});";
			wp_add_inline_script( 'wcprafe-frontend', $script );
		?>
		<?php
	}
}
