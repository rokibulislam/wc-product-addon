<?php
/**
 * Field Hidden
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF\Fields;

use PRAEF\Fields\Base_Field;

/**
 * Field Hidden class
 *
 * @package Product_Addon_Custom_Field
 */
class Field_Hidden extends Base_Field {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Hidden', 'product-addon-custom-field' );
		$this->input_type = 'hidden_field';
		$this->icon       = 'eye-slash';
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
		$value = $field_settings['meta_value'];
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?> >
			<div class="prafe-fields">
				<input type="hidden" name="<?php echo esc_attr( $field_settings['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
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
		$settings = array(
			array(
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Name of the meta key this field will save to', 'product-addon-custom-field' ),
			),
			array(
				'name'      => 'meta_value',
				'title'     => __( 'Meta Value', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Enter the meta value', 'product-addon-custom-field' ),
			),
			array(
				'name'      => 'dynamic',
				'title'     => '',
				'type'      => 'dynamic',
				'section'   => 'advanced',
				'priority'  => 23,
				'help_text' => __( 'Check this option to allow field to be populated dynamically using hooks/query string/shortcode', 'product-addon-custom-field' ),
			),
		);

		return $settings;
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$props = array(
			'template'       => $this->get_type(),
			'name'           => '',
			'meta_value'     => '',
			'is_meta'        => 'yes',
			'id'             => 0,
			'is_new'         => true,
			'prafe_cond' => null,
		);

		return $props;
	}
}
