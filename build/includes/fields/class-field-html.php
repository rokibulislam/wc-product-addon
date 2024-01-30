<?php
/**
 * Field Html
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace PRAEF\Fields;

use PRAEF\Fields\Base_Field;

/**
 * Field Html class
 *
 * @package Product_Addon_Custom_Field
 */
class Field_Html extends Base_Field {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Html', 'product-addon-custom-field' );
		$this->input_type = 'html_field';
		$this->icon       = 'code';
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
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php echo wp_kses_post( $field_settings['html'] ); ?>
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
				'name'      => 'html',
				'title'     => __( 'Html Codes', 'product-addon-custom-field' ),
				'type'      => 'textarea',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Paste your HTML codes, WordPress shortcodes will also work here', 'product-addon-custom-field' ),
			),
			array(
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'product-addon-custom-field' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 12,
				'help_text' => __( 'Name of the meta key this field will save to', 'product-addon-custom-field' ),
			),
			array(
				'name'      => 'prafe_cond',
				'title'     => __( 'Conditional Logic', 'product-addon-custom-field' ),
				'type'      => 'conditional-logic',
				'section'   => 'advanced',
				'priority'  => 30,
				'help_text' => '',
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
			'label'          => $this->get_name(),
			'html'           => sprintf( '%s', __( 'HTML Section', 'product-addon-custom-field' ) ),
			'id'             => 0,
			'is_new'         => true,
			'prafe_cond' => $this->default_conditional_prop(),
		);

		return $props;
	}

	/**
	 * Set full width
	 *
	 * @return boolean
	 */
	public function is_full_width() {

		return true;
	}
}
