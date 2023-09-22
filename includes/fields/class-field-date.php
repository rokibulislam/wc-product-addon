<?php
/**
 * Field Date
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;

/**
 * Field Date class
 *
 * @package MultiStoreX
 */
class Field_Date extends Base_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Date', 'wc-product-addon-custom-field' );
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
				'<div class="contactum-fields"> <input
					id="contactum-date-%s"
					type="text"
					class="datepicker contactum-el-form-control %s"
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

			$script = "jQuery('#contactum-date-{$name}').flatpickr({
				dateFormat: '{$format}'
			});";

			wp_add_inline_script( 'contactum-flatpickr', $script );
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
				'title'         => __( 'Date Format', 'wc-product-addon-custom-field' ),
				'type'          => 'select',
				'is_single_opt' => true,
				'options'       => array(
					'm/d/Y'       => __( 'm/d/Y - (Ex: 04/28/2018)', 'wc-product-addon-custom-field' ),
					'm/d/y'       => __( 'm/d/Y - (Ex: 04/28/18)', 'wc-product-addon-custom-field' ),
					'd/m/Y'       => __( 'm/d/Y - (Ex: 28/04/2018)', 'wc-product-addon-custom-field' ),
					'd.m.Y'       => __( 'd.m.Y - (Ex: 28.04.2018)', 'wc-product-addon-custom-field' ),
					'm/d/Y'       => __( 'm/d/Y - (Ex: 04/28/2018)', 'wc-product-addon-custom-field' ),
					'y/m/d'       => __( 'y/m/d - (Ex: 28/04/18)', 'wc-product-addon-custom-field' ),
					'd-m-y'       => __( 'd-m-y - (Ex: 28-04-18)', 'wc-product-addon-custom-field' ),
					'h:i K'       => __( 'h:i K - (Ex: 08:55 PM)', 'wc-product-addon-custom-field' ),
					'H:i'         => __( 'H:i - (Ex: 20:55 )', 'wc-product-addon-custom-field' ),
					'd.m.Y H:i K' => __( 'd.m.Y H:i K- (Ex: 28.04.2018 20:55 PM)', 'wc-product-addon-custom-field' ),
					'd/m/Y H:i K' => __( 'd/m/Y H:i K- (Ex: 28/04/2018 20:55 PM)', 'wc-product-addon-custom-field' ),
					'd.m.Y H:i'   => __( 'd.m.Y H:i - (Ex: 28.04.2018 20:55)', 'wc-product-addon-custom-field' ),
					'd/m/Y H:i'   => __( 'd/m/Y H:i - (Ex: 28/04/2018 20:55)', 'wc-product-addon-custom-field' ),
					'H:i'         => __( 'H:i - (Ex: 28-04-18 )', 'wc-product-addon-custom-field' ),
				),
				'section'       => 'advanced',
				'priority'      => 24,
				'help_text'     => __( 'The date format', 'wc-product-addon-custom-field' ),
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
			'format' => 'dd/m/y',
		);

		return array_merge( $defaults, $props );
	}
}
