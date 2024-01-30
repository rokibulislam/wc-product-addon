<?php
/**
 * Field Date
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF\Fields;

use PRAEF\Fields\Base_Field;

/**
 * Field Date class
 *
 * @package Product_Addon_Custom_Field
 */
class Field_Date extends Base_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Date', 'product-addon-custom-field' );
		$this->input_type = 'date_field';
		$this->icon       = 'calendar';
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int   $form_id form_id.
	 *
	 * @return void
	 */
	public function render( $field_settings, $form_id ) {
		$value = '';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php
				$this->print_label( $field_settings );
			printf(
				'<div class="prafe-fields"> <input
					id="prafe-date-%s"
					type="text"
					class="datepicker prafe-el-form-control %s"
					data-required="%s"
					data-type="text"
					name="%s"
					placeholder="%s"
					value="%s"
					size="30"
				/> </div>',
				esc_attr( $field_settings['name'] ),
				esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
				esc_attr( $field_settings['required'] ),
				esc_attr( $field_settings['name'] ),
				esc_attr( $field_settings['name'] . '_' . $form_id ),
				esc_attr( $value )
			);
				$this->help_text( $field_settings );
			?>
		</li>
		<?php
			$name   = $field_settings['name'];
			$format = $field_settings['format'];
			
			$script = "jQuery('#prafe-date-{$name}').flatpickr({
				dateFormat: '{$format}'
			});";

			wp_add_inline_script( 'prafe-flatpickr', $script );
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings();

		$settings = array(
			array(
				'name'          => 'format',
				'title'         => __( 'Date Format', 'product-addon-custom-field' ),
				'type'          => 'select',
				'is_single_opt' => true,
				'options'       => array(
					'm/d/Y'       => __( 'm/d/Y - (Ex: 04/28/2018)', 'product-addon-custom-field' ),
					'm/d/y'       => __( 'm/d/Y - (Ex: 04/28/18)', 'product-addon-custom-field' ),
					'd/m/Y'       => __( 'm/d/Y - (Ex: 28/04/2018)', 'product-addon-custom-field' ),
					'd.m.Y'       => __( 'd.m.Y - (Ex: 28.04.2018)', 'product-addon-custom-field' ),
					'm/d/Y'       => __( 'm/d/Y - (Ex: 04/28/2018)', 'product-addon-custom-field' ),
					'y/m/d'       => __( 'y/m/d - (Ex: 28/04/18)', 'product-addon-custom-field' ),
					'd-m-y'       => __( 'd-m-y - (Ex: 28-04-18)', 'product-addon-custom-field' ),
					'h:i K'       => __( 'h:i K - (Ex: 08:55 PM)', 'product-addon-custom-field' ),
					'H:i'         => __( 'H:i - (Ex: 20:55 )', 'product-addon-custom-field' ),
					'd.m.Y H:i K' => __( 'd.m.Y H:i K- (Ex: 28.04.2018 20:55 PM)', 'product-addon-custom-field' ),
					'd/m/Y H:i K' => __( 'd/m/Y H:i K- (Ex: 28/04/2018 20:55 PM)', 'product-addon-custom-field' ),
					'd.m.Y H:i'   => __( 'd.m.Y H:i - (Ex: 28.04.2018 20:55)', 'product-addon-custom-field' ),
					'd/m/Y H:i'   => __( 'd/m/Y H:i - (Ex: 28/04/2018 20:55)', 'product-addon-custom-field' ),
					'H:i'         => __( 'H:i - (Ex: 28-04-18 )', 'product-addon-custom-field' ),
				),
				'section'       => 'advanced',
				'priority'      => 24,
				'help_text'     => __( 'The date format', 'product-addon-custom-field' ),
			),
		);

		return array_merge( $default_options, $settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'format' => 'd/m/y',
		);

		return array_merge( $defaults, $props );
	}
}
