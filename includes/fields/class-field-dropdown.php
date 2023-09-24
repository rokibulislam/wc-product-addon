<?php
/**
 * Field Dropdown
 *
 * @author Rokibul
 * @package WC_Product_Addon_Extra_Field
 */
namespace WCPRAEF\Fields;

use WCPRAEF\Fields\Base_Field;
use WCPRAEF\Fields\Traits\DropDownOption;

/**
 * Field Dropdown class
 *
 * @package WC_Product_Addon_Extra_Field
 */
class Field_Dropdown extends Base_Field {
	use DropDownOption;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'DropDown', 'product-addon-custom-field' );
		$this->input_type = 'dropdown_field';
		$this->icon       = 'caret-square-o-down';
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
		$name     = $field_settings['name'];
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php $this->print_label( $field_settings, $form_id ); ?>
			<div class="wcprafe-fields">
				<select
					class="select wcprafe-el-form-control <?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
					id="<?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
					name="<?php echo esc_attr( $name ); ?>"
					data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
					data-type="select"
				>
				<?php
				if ( ! empty( $field_settings['first'] ) ) {
					?>
					<option value=""> <?php echo esc_attr( $field_settings['first'] ); ?> </option>
					<?php
				}
				if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
					foreach ( $field_settings['options'] as $value => $option ) {
						$current_select = selected( $selected, $option['value'], false );
						printf( '<option value="%s" %s> %s </option>', esc_attr( $option['value'] ), esc_attr( $current_select ), esc_attr( $option['value'] ) );
					}
				}
				?>
				</select>
			</div>
			<?php $this->help_text( $field_settings ); ?>
		</li>
		<?php
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options  = $this->get_default_option_settings();
		$dropdown_options = array(
			$this->get_default_option_dropdown_settings( $this->multiple ),
			array(
				'name'      => 'first',
				'title'     => __( 'Select Text', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 13,
				'help_text' => __( 'help', 'product-addon-custom-field' ),
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
		$props    = array(
			'selected' => '',
			'image'    => false,
			'options'  => array(
				array(
					'label' => 'option',
					'value' => 'option',
				),
				array(
					'label' => 'option-2',
					'value' => 'option-2',
				),
				array(
					'label' => 'option-3',
					'value' => 'option-3',
				),
			),
			'first'    => __( '— Select —', 'product-addon-custom-field' ),
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
		$val = $post_data[ $field['name'] ];

		return isset( $field['options'][ $val ] ) ? $field['options'][ $val ] : $val;
	}
}
