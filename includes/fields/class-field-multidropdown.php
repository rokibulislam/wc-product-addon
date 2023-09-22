<?php
/**
 * Field Multidropdown
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;

/**
 * Field MultiDropdown class
 *
 * @package MultiStoreX
 */
class Field_MultiDropdown extends Field_Dropdown {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Multi Select', 'wc-product-addon-custom-field' );
		$this->input_type = 'multiple_select';
		$this->icon       = 'list-ul';
		$this->multiple   = false;
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
		$selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
		$selected = is_array( $selected ) ? $selected : [ $selected ];
		$name     = $field_settings['name'] . '[]';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php $this->print_label( $field_settings, $form_id ); ?>
				<div class="contactum-fields">

					<select
						class="multiselect contactum-el-form-control <?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
						id="<?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
						name="<?php echo esc_attr( $name ); ?>"
						multiple
						data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
						data-type="multiselect"
					>
					<?php
					if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
						foreach ( $field_settings['options'] as  $option ) {
							$current_select = selected( in_array( $option['value'], $selected ), true, false );
							printf( '<option value="%s" %s> %s </option>', esc_attr( $option['value'] ), esc_attr( $current_select ), esc_attr( $option['value'] ) );
						}
					}
					?>
					</select>
				</div>
			<?php $this->help_text( $field_settings ); ?>
		</li>
		<?php
			$id = esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id );

			$script = "jQuery(function($) {
				new Choices('#{$id}', {
					removeItemButton: true
				});
			});";
			wp_add_inline_script( 'contactum-frontend', $script );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'selected' => array(),
			'image'    => false,
			'options'  => array(
				array(
					'label' => 'option',
					'value' => 'option',
					'photo' => '',
				),
				array(
					'label' => 'option-2',
					'value' => 'option-2',
					'photo' => '',
				),
				array(
					'label' => 'option-3',
					'value' => 'option-3',
					'photo' => '',
				),
			),
			'first'    => __( '— Select —', 'contactum' ),
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
		$entry_value = ( is_array( $post_data[ $field['name'] ] ) && $post_data[ $field['name'] ] ) ? $post_data[ $field['name'] ] : array();

		if ( $entry_value ) {
			$new_val = array();

			foreach ( $entry_value as $option_key ) {
				$new_val[] = isset( $field['options'][ $option_key ] ) ? $field['options'][ $option_key ] : $option_key;
			}

			$entry_value = implode( CONTACTUM_SEPARATOR, $new_val );
		} else {
			$entry_value = '';
		}

		return $entry_value;
	}
}
