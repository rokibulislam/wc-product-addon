<?php
/**
 * Field Email
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */

namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;
use WCPRAEF\Fields\Traits\Textoption;

/**
 * Field Email class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Field_Email extends Base_Field {
	use Textoption;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Email', 'product-addon-custom-field' );
		$this->input_type = 'email_field';
		$this->icon       = 'envelope-o';
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int	$form_id form_id.
	 *
	 * @return void
	 */
	public function render( $field_settings, $form_id ) {
		$value = $field_settings['default'];
		if ( isset( $field_settings['auto_populate'] ) && $field_settings['auto_populate'] === 'yes' && is_user_logged_in() ) {
			return;
		}
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
		<?php
		$this->print_label( $field_settings, $form_id );
		printf(
			'<div class="wcprafe-fields"> <input
		id="%s"
		type="email"
		class="wcprafe-el-form-control %s"
		data-duplicate="%s"
		data-required="%s"
		data-type="email"
		name="%s"
		placeholder="%s"
		value="%s"
		size="%s"
		autocomplete="url"
		/>
		</div>',
			esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
			esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
			isset( $field_settings['duplicate'] ) ? esc_attr( $field_settings['duplicate'] ) : 'no',
			esc_attr( $field_settings['required'] ),
			esc_attr( $field_settings['name'] ),
			esc_attr( $field_settings['placeholder'] ),
			esc_attr( $value ),
			esc_attr( $field_settings['size'] )
		);

		$this->help_text( $field_settings );
		?>
		</li>
		<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options      = $this->get_default_option_settings();
		$default_text_options = $this->get_default_text_option_settings();

		return array_merge( $default_options, $default_text_options );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'duplicate' => '',
		);

		return array_merge( $defaults, $props );
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
		if ( isset( $field['auto_populate'] ) && $field['auto_populate'] === 'yes' && is_user_logged_in() ) {
			$user = wp_get_current_user();

			if ( ! empty( $user->user_email ) ) {
				return $user->user_email;
			}
		}

		$value = ! empty( $post_data[ $field['name'] ] ) ? $post_data[ $field['name'] ] : '';

		return sanitize_text_field( trim( $value ) );
	}
}