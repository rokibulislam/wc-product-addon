<?php
/**
 * Field Checkbox
 *
 * @author Kamrul
 * @package MultiStoreX
 */

namespace Contactum\Fields;

use Contactum\Fields\Contactum_Field;
use Contactum\Fields\Traits\DropDownOption;

/**
 * Field Checkbox class
 *
 * @package MultiStoreX
 */
class Field_Checkbox extends Contactum_Field {
	use DropDownOption;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Checkbox', 'contactum' );
		$this->input_type = 'checkbox_field';
		$this->icon       = 'check-square-o';
		$this->multiple   = true;
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
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?> >
			<?php $this->print_label( $field_settings, $form_id ); ?>
			<?php
			$class = $field_settings['layout'] === 'inline' ? 'show-inline ' : 'show';
			if ( $field_settings['photo_value'] ) {
				$class .= 'list_type_image';
			}
			?>
			<div class="contactum-fields">
				<div class="<?php echo esc_attr( $class ); ?>"
				data-required="<?php echo esc_attr( $field_settings['required'] ); ?>" data-type="radio">
				<?php
				if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
					foreach ( $field_settings['options'] as $value => $option ) {
						?>
					<label>
						<?php
						printf(
							'<input name="%s[]" class="%s" type="checkbox" value="%s" %s/>',
							esc_attr( $field_settings['name'] ),
							esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
							esc_attr( $option['value'] ),
							in_array( $option['value'], $selected ) ? ' checked="checked"' : '',
						);
						if ( $option['photo'] !== '' ) {
							printf( '<img src="%s"  width="50px"/>', esc_attr( $option['photo'] ) );
						} else {
							echo $option['label']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
						</label>
						<?php
					}
				}
				$this->help_text( $field_settings );
				?>
				</div>
			</li>
			<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options  = $this->get_default_option_settings( true, array( 'width' ) );
		$dropdown_options = array(
			$this->get_default_option_dropdown_settings( $this->multiple ),
			array(
				'name'      => 'layout',
				'title'     => __( 'Layout', 'contactum' ),
				'type'      => 'select',
				'options'   => array(
					'default' => __( 'Default', 'contactum' ),
					'inline'  => __( 'Inline', 'contactum' ),
				),
				'default'   => 'default',
				'inline'    => true,
				'section'   => 'advanced',
				'priority'  => 23,
				'help_text' => __( 'Show this option in an inline list', 'contactum' ),
			),
		);

		return array_merge( $default_options, $dropdown_options );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();

		$props = array(
			'selected' => array(),
			'layout'   => 'default',
			'image'    => true,
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

		if ( $entry_value && $this->multiple ) {
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
